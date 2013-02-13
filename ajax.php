<?php

/*
This file is part of yourTinyTodo by the yourTinyTodo community.
Copyrights for portions of this file are retained by their owners.

Based on myTinyTodo by Max Pozdeev
(C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/ 

set_error_handler('myErrorHandler');
set_exception_handler('myExceptionHandler');

require_once(dirname(__FILE__).'/init.php');

$db = DBConnection::instance();

if(isset($_GET['loadLists']))
{
	if($needAuth && !is_logged()) $sqlWhere = 'WHERE published=1';
	else $sqlWhere = '';
	$t = array();
	$t['total'] = 0;
	$q = $db->dq("SELECT * FROM {$db->prefix}lists $sqlWhere ORDER BY ow ASC, id ASC");
	while($r = $q->fetch_assoc($q))
	{
		$t['total']++;
		$t['list'][] = prepareList($r);
	}
	jsonExit($t);
}
elseif(isset($_GET['loadTasks']))
{
	stop_gpc($_GET);
	$listId = (int)_get('list');
	check_read_access($listId);
	$t = loadTasks($listId, _get('compl'), _get('t'), _get('s'), (int)_get('sort'), _get('setCompl'), _get('setNotification'));
	jsonExit($t);
}
elseif(isset($_GET['newTask']))
{
	stop_gpc($_POST);
	$listId = (int)_post('list');
	check_write_access($listId);
	$t = array();
	$t['total'] = 0;
	$title = trim(_post('title'));
	$prio = 0;
	$tags = '';
	if(Config::get('smartsyntax') != 0)
	{
		$a = parse_smartsyntax($title);
		if($a === false) {
			jsonExit($t);
		}
		$title = $a['title'];
		$prio = $a['prio'];
		$tags = $a['tags'];
	}
	if($title == '') {
		jsonExit($t);
	}

	$author = (Config::get('multiuser') == 1 && isset($_SESSION['userid']))?intval($_SESSION['userid']):null;
	if(Config::get('autotag')) $tags .= ','._post('tag');
	$ow = 1 + (int)$db->sq("SELECT MAX(ow) FROM {$db->prefix}todolist WHERE list_id=$listId AND compl=0");
	$db->ex("BEGIN");
	$db->dq("INSERT INTO {$db->prefix}todolist (uuid,list_id,title,d_created,d_edited,ow,prio,author) VALUES (?,?,?,?,?,?,?,?)",
				array(generateUUID(), $listId, $title, time(), time(), $ow, $prio, $author) );
	$id = $db->last_insert_id($db->prefix.'todolist');
	if($tags != '')
	{
		$aTags = prepareTags($tags);
		if($aTags) {
			addTaskTags($id, $aTags['ids'], $listId);
			$db->ex("UPDATE {$db->prefix}todolist SET tags=?,tags_ids=? WHERE id=$id", array(implode(',',$aTags['tags']), implode(',',$aTags['ids'])));
		}
	}
	$db->ex("COMMIT");

	addNotification(_r('n_task_created', $title), Notification::NOTIFICATION_TYPE_TASK_CREATED, $listId);

	$r = $db->sqa("SELECT * FROM {$db->prefix}todolist WHERE id=$id");
	$t['list'][] = prepareTaskRow($r);
	$t['total'] = 1;
	jsonExit($t);
}
elseif(isset($_GET['fullNewTask']))
{
	stop_gpc($_POST);
	$listId = (int)_post('list');
	check_write_access($listId);
	$title = trim(_post('title'));
	$note = str_replace("\r\n", "\n", trim(_post('note')));
	$prio = (int)_post('prio');
	$duration = (float)str_replace(',','.',_post('duration'));
	if($prio < -1) $prio = -1;
	elseif($prio > 2) $prio = 2;
	$duedate = parse_duedate(trim(_post('duedate')));
	$duedate_h = intval(trim(_post('duedate_h')));
	$duedate_m = intval(trim(_post('duedate_m')));
	if(!empty($duedate)) {
		$duedate .= ' '.$duedate_h.':'.$duedate_m;
	}
	$t = array();
	$t['total'] = 0;
	if($title == '') {
		jsonExit($t);
	}
	$tags = trim(_post('tags'));
	$author = (Config::get('multiuser') == 1 && isset($_SESSION['userid']))?(int)$_SESSION['userid']:null;
	if(Config::get('autotag')) $tags .= ','._post('tag');
	$ow = 1 + (int)$db->sq("SELECT MAX(ow) FROM {$db->prefix}todolist WHERE list_id=$listId AND compl=0");
	$db->ex("BEGIN");
	$db->dq("INSERT INTO {$db->prefix}todolist (uuid,list_id,title,d_created,d_edited,ow,prio,note,duedate,duration,author) VALUES(?,?,?,?,?,?,?,?,?,?,?)",
				array(generateUUID(), $listId, $title, time(), time(), $ow, $prio, $note, $duedate, $duration, $author) );
	$id = $db->last_insert_id($db->prefix.'todolist');
	if($tags != '')
	{
		$aTags = prepareTags($tags);
		if($aTags) {
			addTaskTags($id, $aTags['ids'], $listId);
			$db->ex("UPDATE {$db->prefix}todolist SET tags=?,tags_ids=? WHERE id=$id", array(implode(',',$aTags['tags']), implode(',',$aTags['ids'])));
		}
	}
	$db->ex("COMMIT");
	addNotification(_r('n_task_created', $title), Notification::NOTIFICATION_TYPE_TASK_CREATED, $listId);

	$r = $db->sqa("SELECT * FROM {$db->prefix}todolist WHERE id=$id");
	$t['list'][] = prepareTaskRow($r);
	$t['total'] = 1;
	jsonExit($t);
}
elseif(isset($_GET['deleteTask']))
{
	$id = (int)_post('id');
	$deleted = deleteTask($id);
	$t = array();
	$t['total'] = $deleted;
	$t['list'][] = array('id'=>$id);
	jsonExit($t);
}
elseif(isset($_GET['completeTask']))
{
	check_write_access();
	$id = (int)_post('id');
	$compl = _post('compl') ? 1 : 0;
	$listId = (int)$db->sq("SELECT list_id FROM {$db->prefix}todolist WHERE id=$id");
	if($compl) 	$ow = 1 + (int)$db->sq("SELECT MAX(ow) FROM {$db->prefix}todolist WHERE list_id=$listId AND compl=1");
	else $ow = 1 + (int)$db->sq("SELECT MAX(ow) FROM {$db->prefix}todolist WHERE list_id=$listId AND compl=0");
	$dateCompleted = $compl ? time() : 0;
	$db->dq("UPDATE {$db->prefix}todolist SET compl=$compl,ow=$ow,d_completed=?,d_edited=? WHERE id=$id",
				array($dateCompleted, time()) );

	$title = $db->sq("SELECT title FROM {$db->prefix}todolist WHERE id=$id");
	addNotification(_r('n_task_completed', $title), Notification::NOTIFICATION_TYPE_TASK_COMPLETED, $listId, $id);

	$t = array();
	$t['total'] = 1;
	$r = $db->sqa("SELECT * FROM {$db->prefix}todolist WHERE id=$id");
	$t['list'][] = prepareTaskRow($r);
	jsonExit($t);
}
elseif(isset($_GET['editNote']))
{
	check_write_access();
	$id = (int)_post('id');
	stop_gpc($_POST);
	$note = str_replace("\r\n", "\n", trim(_post('note')));
	$db->dq("UPDATE {$db->prefix}todolist SET note=?,d_edited=? WHERE id=$id", array($note, time()) );

	$title = $db->sq("SELECT title FROM {$db->prefix}todolist WHERE id=$id");
	$listId = (int)$db->sq("SELECT list_id FROM {$db->prefix}todolist WHERE id=$id");
	addNotification(_r('n_task_changed_note', $title), Notification::NOTIFICATION_TYPE_TASK_CHANGED, $listId, $id);

	$t = array();
	$t['total'] = 1;
	$t['list'][] = array('id'=>$id, 'note'=>nl2br(escapeTags($note)), 'noteText'=>(string)$note);
	jsonExit($t);
}
elseif(isset($_GET['editTask']))
{
	check_write_access();
	$id = (int)_post('id');
	stop_gpc($_POST);
	$title = trim(_post('title'));
	$note = str_replace("\r\n", "\n", trim(_post('note')));
	$duration_h = (float)str_replace(',','.',_post('duration_h'));
	$duration_m = intval(_post('duration_m'));
	$duration = $duration_h + ($duration_m / 60);
	$prio = (int)_post('prio');
	if($prio < -1) $prio = -1;
	elseif($prio > 2) $prio = 2;
	$duedate = parse_duedate(trim(_post('duedate')));
	$duedate_h = intval(trim(_post('duedate_h')));
	$duedate_m = intval(trim(_post('duedate_m')));
	if(!empty($duedate)) {
		$duedate .= ' '.$duedate_h.':'.$duedate_m;
	}
	$t = array();
	$t['total'] = 0;
	if($title == '') {
		jsonExit($t);
	}
	$listId = $db->sq("SELECT list_id FROM {$db->prefix}todolist WHERE id=$id");
	$tags = trim(_post('tags'));
	$db->ex("BEGIN");
	$db->ex("DELETE FROM {$db->prefix}tag2task WHERE task_id=$id");
	$aTags = prepareTags($tags);
	if($aTags) {
		$tags = implode(',', $aTags['tags']);
		$tags_ids = implode(',',$aTags['ids']);
		addTaskTags($id, $aTags['ids'], $listId);
	}
	$db->dq("UPDATE {$db->prefix}todolist SET title=?,note=?,prio=?,tags=?,tags_ids=?,duedate=?,d_edited=?,duration=? WHERE id=$id",
			array($title, $note, $prio, $tags, $tags_ids, $duedate, time(), $duration) );
	$db->ex("COMMIT");

	addNotification(_r('n_task_changed_all', $title), Notification::NOTIFICATION_TYPE_TASK_CHANGED, $listId, $id);

	$r = $db->sqa("SELECT * FROM {$db->prefix}todolist WHERE id=$id");
	if($r) {
		$t['list'][] = prepareTaskRow($r);
		$t['total'] = 1;
	}
	jsonExit($t);
}
elseif(isset($_GET['changeOrder']))
{
	check_write_access();
	stop_gpc($_POST);
	$s = _post('order');
	parse_str($s, $order);
	$t = array();
	$t['total'] = 0;
	if($order)
	{
		$ad = array();
		foreach($order as $id=>$diff) {
			$ad[(int)$diff][] = (int)$id;
		}
		$db->ex("BEGIN");
		foreach($ad as $diff=>$ids) {
			if($diff >=0) $set = "ow=ow+".$diff;
			else $set = "ow=ow-".abs($diff);
			$db->dq("UPDATE {$db->prefix}todolist SET $set,d_edited=? WHERE id IN (".implode(',',$ids).")", array(time()) );
		}
		$db->ex("COMMIT");
		$t['total'] = 1;
	}
	jsonExit($t);
}
elseif(isset($_POST['login']))
{
	$t = array('logged' => 0);
	if(!$needAuth) {
		$t['disabled'] = 1;
		jsonExit($t);
	}
	stop_gpc($_POST);

	if(Config::get('multiuser') == 1)
	{
		$password = _post('password');
		$username = _post('username');
		$result = $db->dq("SELECT role,id FROM {$db->prefix}users WHERE username = ? AND MD5(CONCAT(?,uuid)) = password", array($username, $password) );
		$row = $result->fetch_assoc();
		if($result && is_array($row) && count($row) > 0) {
			$t['logged'] = 1;

			session_regenerate_id(1);
			$_SESSION['logged'] = 1;
			$_SESSION['userid'] = $row['id'];
			$_SESSION['role'] = $row['role'];

			$t['role'] = $row['role'];
			$t['userid'] = $row['id'];
		}
	}
	else
	{
		$password = _post('password');
		if($password == Config::get('password')) {
			$t['logged'] = 1;
			session_regenerate_id(1);
			$_SESSION['logged'] = 1;
		}
	}


	jsonExit($t);
}
elseif(isset($_POST['logout']))
{
	unset($_SESSION['logged']);
	unset($_SESSION['userid']);
	unset($_SESSION['role']);
	$t = array('logged' => 0);
	jsonExit($t);
}
elseif(isset($_GET['suggestTags']))
{
	$listId = (int)_get('list');
	check_read_access($listId);
	$begin = trim(_get('q'));
	$limit = (int)_get('limit');
	if($limit<1) $limit = 8;
	$q = $db->dq("SELECT name,id FROM {$db->prefix}tags INNER JOIN {$db->prefix}tag2task ON id=tag_id WHERE list_id=$listId AND name LIKE ".
					$db->quoteForLike('%s%%',$begin) ." GROUP BY tag_id ORDER BY name LIMIT $limit");
	$s = '';
	while($r = $q->fetch_row()) {
		$s .= "$r[0]|$r[1]\n";
	}
	echo htmlarray($s);
	exit; 
}
elseif(isset($_GET['setPrio']))
{
	check_write_access();
	$id = (int)$_GET['setPrio'];
	$prio = (int)_get('prio');
	if($prio < -1) $prio = -1;
	elseif($prio > 2) $prio = 2;
	$db->ex("UPDATE {$db->prefix}todolist SET prio=$prio,d_edited=? WHERE id=$id", array(time()) );

	$title = $db->sq("SELECT title FROM {$db->prefix}todolist WHERE id=$id");
	$listId = (int)$db->sq("SELECT list_id FROM {$db->prefix}todolist WHERE id=$id");
	addNotification(_r('n_task_changed_priority', $title), Notification::NOTIFICATION_TYPE_TASK_CHANGED, $listId, $id);

	$t = array();
	$t['total'] = 1;
	$t['list'][] = array('id'=>$id, 'prio'=>$prio);
	jsonExit($t);
}
elseif(isset($_GET['tagCloud']))
{
	$listId = (int)_get('list');
	check_read_access($listId);

	$q = $db->dq("SELECT name,tag_id,COUNT(tag_id) AS tags_count FROM {$db->prefix}tag2task INNER JOIN {$db->prefix}tags ON tag_id=id ".
						"WHERE list_id=$listId GROUP BY (tag_id) ORDER BY tags_count ASC");
	$at = array();
	$ac = array();
	while($r = $q->fetch_assoc()) {
		$at[] = array('name'=>$r['name'], 'id'=>$r['tag_id']);
		$ac[] = $r['tags_count'];
	}

	$t = array();
	$t['total'] = 0;
	$count = sizeof($at);
	if(!$count) {
		jsonExit($t);
	}

	$qmax = max($ac);
	$qmin = min($ac);
	if($count >= 10) $grades = 10;
	else $grades = $count;
	$step = ($qmax - $qmin)/$grades;
	foreach($at as $i=>$tag)
	{
		$t['cloud'][] = array('tag'=>htmlarray($tag['name']), 'id'=>(int)$tag['id'], 'w'=> tag_size($qmin,$ac[$i],$step) );
	}
	$t['total'] = $count;
	jsonExit($t);
}
elseif(isset($_GET['addList']))
{
	check_write_access();
	stop_gpc($_POST);
	$t = array();
	$t['total'] = 0;
	$name = str_replace(array('"',"'",'<','>','&'),array('','','','',''),trim(_post('name')));
	$ow = 1 + (int)$db->sq("SELECT MAX(ow) FROM {$db->prefix}lists");
	$db->dq("INSERT INTO {$db->prefix}lists (uuid,name,ow,d_created,d_edited) VALUES (?,?,?,?,?)",
				array(generateUUID(), $name, $ow, time(), time()) );
	$id = $db->last_insert_id($db->prefix.'lists');

	addNotification(_r('n_list_added', $name), Notification::NOTIFICATION_TYPE_LIST_ADDED, $id);

	$t['total'] = 1;
	$r = $db->sqa("SELECT * FROM {$db->prefix}lists WHERE id=$id");
	$t['list'][] = prepareList($r);
	jsonExit($t);
}
elseif(isset($_GET['renameList']))
{
	check_write_access();
	stop_gpc($_POST);
	$t = array();
	$t['total'] = 0;
	$id = (int)_post('list');
	$old_name = $db->sq("SELECT name FROM {$db->prefix}lists WHERE id=$id");
	$name = str_replace(array('"',"'",'<','>','&'),array('','','','',''),trim(_post('name')));
	$db->dq("UPDATE {$db->prefix}lists SET name=?,d_edited=? WHERE id=$id", array($name, time()) );
	$t['total'] = $db->affected();

	addNotification(_r('n_list_renamed', array($old_name, $name)), Notification::NOTIFICATION_TYPE_LIST_RENAMED, $id);

	$r = $db->sqa("SELECT * FROM {$db->prefix}lists WHERE id=$id");
	$t['list'][] = prepareList($r);
	jsonExit($t);
}
elseif(isset($_GET['deleteList']))
{
	check_write_access();
	stop_gpc($_POST);
	$t = array();
	$t['total'] = 0;
	$id = (int)_post('list');
	$name = $db->sq("SELECT name FROM {$db->prefix}lists WHERE id=$id");

	$db->ex("BEGIN");
	$db->ex("DELETE FROM {$db->prefix}lists WHERE id=$id");
	$t['total'] = $db->affected();

	addNotification(_r('n_list_deleted', $name), Notification::NOTIFICATION_TYPE_LIST_DELETED, $id);

	if($t['total']) {
		$db->ex("DELETE FROM {$db->prefix}tag2task WHERE list_id=$id");
		$db->ex("DELETE FROM {$db->prefix}todolist WHERE list_id=$id");
	}
	$db->ex("COMMIT");
	jsonExit($t);
}
elseif(isset($_GET['setSort']))
{
	check_write_access();
	$listId = (int)_post('list');
	$sort = (int)_post('sort');
	if($sort < 0 || $sort > 104) $sort = 0;
	elseif($sort < 101 && $sort > 4) $sort = 0;
	$db->ex("UPDATE {$db->prefix}lists SET sorting=$sort,d_edited=? WHERE id=$listId", array(time()));
	jsonExit(array('total'=>1));
}
elseif(isset($_GET['publishList']))
{
	check_write_access();
	$listId = (int)_post('list');
	$publish = (int)_post('publish');
	$db->ex("UPDATE {$db->prefix}lists SET published=?,d_created=? WHERE id=$listId", array($publish ? 1 : 0, time()));
	jsonExit(array('total'=>1));
}
elseif(isset($_GET['moveTask']))
{
	check_write_access();
	$id = (int)_post('id');
	$fromId = (int)_post('from');
	$toId = (int)_post('to');
	$result = moveTask($id, $toId);
	$t = array('total' => $result ? 1 : 0);
	if($fromId == -1 && $result && $r = $db->sqa("SELECT * FROM {$db->prefix}todolist WHERE id=$id")) {
		$t['list'][] = prepareTaskRow($r);
	}
	jsonExit($t);
}
elseif(isset($_GET['changeListOrder']))
{
	check_write_access();
	stop_gpc($_POST);
	$order = (array)_post('order');
	$t = array();
	$t['total'] = 0;
	if($order)
	{
		$a = array();
		$setCase = '';
		foreach($order as $ow=>$id) {
			$id = (int)$id;
			$a[] = $id;
			$setCase .= "WHEN id=$id THEN $ow\n";
		}
		$ids = implode($a, ',');
		$db->dq("UPDATE {$db->prefix}lists SET d_edited=?, ow = CASE\n $setCase END WHERE id IN ($ids)",
					array(time()) );
		$t['total'] = 1;
	}
	jsonExit($t);
}
elseif(isset($_GET['parseTaskStr']))
{
	check_write_access();
	stop_gpc($_POST);
	$t = array(
		'title' => trim(_post('title')),
		'prio' => 0,
		'tags' => ''
	);
	if(Config::get('smartsyntax') != 0 && (false !== $a = parse_smartsyntax($t['title'])))
	{
		$t['title'] = $a['title'];
		$t['prio'] = $a['prio'];
		$t['tags'] = $a['tags'];
	}
	jsonExit($t);
}
elseif(isset($_GET['clearCompletedInList']))
{
	check_write_access();
	stop_gpc($_POST);
	$t = array();
	$t['total'] = 0;
	$listId = (int)_post('list');
	$db->ex("BEGIN");
	$db->ex("DELETE FROM {$db->prefix}tag2task WHERE task_id IN (SELECT id FROM {$db->prefix}todolist WHERE list_id=? and compl=1)", array($listId));
	$db->ex("DELETE FROM {$db->prefix}todolist WHERE list_id=$listId and compl=1");
	$t['total'] = $db->affected();
	$db->ex("COMMIT");
	jsonExit($t);
}
elseif(isset($_GET['setShowNotesInList']))
{
	check_write_access();
	$listId = (int)_post('list');
	$flag = (int)_post('shownotes');
	$bitwise = ($flag == 0) ? 'taskview & ~2' : 'taskview | 2';
	$db->dq("UPDATE {$db->prefix}lists SET taskview=$bitwise WHERE id=$listId");
	jsonExit(array('total'=>1));
}
elseif(isset($_GET['setHideList']))
{
	check_write_access();
	$listId = (int)_post('list');
	$flag = (int)_post('hide');
	$bitwise = ($flag == 0) ? 'taskview & ~4' : 'taskview | 4';
	$db->dq("UPDATE {$db->prefix}lists SET taskview=$bitwise WHERE id=$listId");
	jsonExit(array('total'=>1));	
}
elseif(isset($_GET['createuser']))
{
	check_admin_access();
	stop_gpc($_POST);
	$username = _post('yttusername');
	$password = _post('yttpassword');
	$email = _post('yttemail');
	$role = (int)_post('yttrole');


	// check input
	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	if(empty($username) || empty($password) || empty($email) || !in_array($role, array(1,2,3)) || !preg_match($regex, $email))
	{
		jsonExit(array('error' => 1)); // data invalid
	}

	$result = $db->dq("SELECT * FROM {$db->prefix}users WHERE username = ?", array($username));
	$row = $result->fetch_assoc();
	if($result && is_array($row) && count($row) > 0)
	{
		jsonExit(array('error' => 2)); // username already exists
	}

	$uuid = generateUUID();
	$db->dq("INSERT INTO {$db->prefix}users (uuid,username,password,email,d_created,role) VALUES(?,?,?,?,?,?)",
		array($uuid, $username, hashPassword($password, $uuid), $email, time(), $role) );
	$id = $db->last_insert_id($db->prefix.'users');
	if($id > 0)
	{
		jsonExit(array('error' => 0)); // everything is fine
	}
	else
	{
		jsonExit(array('error' => 3)); // error creating user
	}
}
elseif(isset($_GET['edituser']))
{
	check_admin_access();
	stop_gpc($_POST);
	$username = _post('yttusername');
	$password = _post('yttpassword');
	$email = _post('yttemail');
	$role = (int)_post('yttrole');
	$userid = (int)_post('yttuserid');
	$notification = (int)_post('yttnotification');


	// check input
	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	if(empty($username) || empty($email) || !in_array($role, array(1,2,3)) || empty($userid) || !preg_match($regex, $email))
	{
		jsonExit(array('error' => 1)); // data invalid
	}

	$result = $db->dq("SELECT * FROM {$db->prefix}users WHERE username = ? AND id != ?", array($username,$userid));
	$row = $result->fetch_assoc();
	if($result && is_array($row) && count($row) > 0)
	{
		jsonExit(array('error' => 2)); // username already exists
	}

	$result = $db->dq("SELECT uuid FROM {$db->prefix}users WHERE id = ?", array($userid));
	$row = $result->fetch_assoc();
	if($result && is_array($row) && count($row) > 0) {
		if(!empty($password)) {
			$uuid = $row['uuid'];
			$updateresult = $db->dq("UPDATE {$db->prefix}users SET username= ?, password = ?, email = ?, role = ? WHERE id = ?",
				array($username, hashPassword($password, $uuid), $email, $role, $userid) );
		} else {
			$updateresult = $db->dq("UPDATE {$db->prefix}users SET username= ?, email = ?, role = ? WHERE id = ?",
				array($username, $email, $role, $userid) );
		}
	} else {
		jsonExit(array('error' => 1)); // data invalid
	}

	if($notification == 1) {
		NotificationListener::enableNotification(NotificationListener::LISTENER_TYPE_GLOBAL, null, false, $userid);
	} else {
		NotificationListener::disableNotification(NotificationListener::LISTENER_TYPE_GLOBAL, null, $userid);
	}

	if($updateresult)
	{
		jsonExit(array('error' => 0)); // everything is fine
	}
	else
	{
		jsonExit(array('error' => 4)); // error updating user
	}
}
elseif(isset($_GET['deleteuser']))
{
	check_admin_access();
	stop_gpc($_POST);
	$userid = (int)_post('yttuserid');

	// check input
	if(empty($userid))
	{
		jsonExit(array('error' => 1)); // data invalid
	}

	$result = $db->dq("DELETE FROM {$db->prefix}users WHERE id = ?", array($userid));
	if($result->affected())
	{
		jsonExit(array('error' => 0)); // done
	}
	else
	{
		jsonExit(array('error' => 2)); // error
	}
}
elseif(isset($_GET['markread']))
{
	check_write_access();
	stop_gpc($_POST);
	$notificationid = (int)_post('yttnotificationid');
	$current_user_id = (int)$_SESSION['userid'];
	$result = $db->dq("UPDATE {$db->prefix}notifications SET shown = 1 WHERE id = ? AND user_id = ?", array($notificationid, $current_user_id));
	if($result->affected())
	{
		jsonExit(array('error' => 0)); // done
	}
	else
	{
		jsonExit(array('error' => 1)); // error
	}
}
elseif(isset($_GET['markallasread']))
{
	check_write_access();
	stop_gpc($_POST);
	$current_user_id = (int)$_SESSION['userid'];
	$result = $db->dq("UPDATE {$db->prefix}notifications SET shown = 1 WHERE user_id = ?", array($current_user_id));
	if($result->affected())
	{
		jsonExit(array('error' => 0)); // done
	}
	else
	{
		jsonExit(array('error' => 1)); // error
	}
}
elseif(isset($_GET['countNotifications']))
{
	$notifications_count = (Config::get('multiuser') == 1)?Notification::getUnreadCount():0;
	jsonExit(array('count' => $notifications_count));
}
elseif(isset($_GET['trackWorkTime']))
{
	check_write_access();
	stop_gpc($_POST);
	$taskid = (int)_post('ytt_taskId');
	$time = (int)_post('ytt_time');
	$date = _post('ytt_date');
	TimeTracker::trackTime($taskid, $time, $date);
	jsonExit(array('done' => 1));
}
elseif(isset($_GET['addComment']))
{
	check_write_access();
	stop_gpc($_POST);
	$taskid = (int)_post('ytt_taskId');
	$comment = _post('ytt_comment');
	$current_user_id = (int)$_SESSION['userid'];

	$db->dq("INSERT INTO {$db->prefix}comments (task_id,user_id,comment) VALUES(?,?,?)",
		array($taskid, $current_user_id, $comment) );

	jsonExit(array('done' => 1, 'user' => getUserName($current_user_id), 'date' => date(Config::get('dateformat').' H:i')));
}

###################################################################################################

function loadTasks($listId, $compl = 0, $tag = '', $s = '', $sort = 0, $setCompl = 0, $setNotification = null) {
	$db = DBConnection::instance();
	$sqlWhere = $inner = '';
	if($listId == -1) {
		$userLists = getUserListsSimple();
		$sqlWhere .= " AND {$db->prefix}todolist.list_id IN (". implode(array_keys($userLists), ','). ") ";
	}
	else $sqlWhere .= " AND {$db->prefix}todolist.list_id=". $listId;
	if($compl == 0) $sqlWhere .= ' AND compl=0';

	$tag = trim($tag);
	if($tag != '')
	{
		$at = explode(',', $tag);
		$tagIds = array();
		$tagExIds = array();
		foreach($at as $i=>$atv) {
			$atv = trim($atv);
			if($atv == '' || $atv == '^') continue;
			if(substr($atv,0,1) == '^') {
				$tagExIds[] = getTagId(substr($atv,1));
			} else {
				$tagIds[] = getTagId($atv);
			}
		}

		if(sizeof($tagIds) > 1) {
			$inner .= "INNER JOIN (SELECT task_id, COUNT(tag_id) AS c FROM {$db->prefix}tag2task WHERE list_id=$listId AND tag_id IN (".
				implode(',',$tagIds). ") GROUP BY task_id) AS t2t ON id=t2t.task_id";
			$sqlWhere = " AND c=". sizeof($tagIds); //overwrite sqlWhere!
		}
		elseif($tagIds) {
			$inner .= "INNER JOIN {$db->prefix}tag2task ON id=task_id";
			$sqlWhere .= " AND tag_id = ". $tagIds[0];
		}

		if($tagExIds) {
			$sqlWhere .= " AND id NOT IN (SELECT DISTINCT task_id FROM {$db->prefix}tag2task WHERE list_id=$listId AND tag_id IN (".
				implode(',',$tagExIds). "))"; //DISTINCT ?
		}
	}

	$s = trim($s);
	if($s != '') $sqlWhere .= " AND (title LIKE ". $db->quoteForLike("%%%s%%",$s). " OR note LIKE ". $db->quoteForLike("%%%s%%",$s). ")";
	$sort = $sort;
	$sqlSort = "ORDER BY compl ASC, ";
	if($sort == 1) $sqlSort .= "prio DESC, ddn ASC, duedate ASC, ow ASC";		// byPrio
	elseif($sort == 101) $sqlSort .= "prio ASC, ddn DESC, duedate DESC, ow DESC";	// byPrio (reverse)
	elseif($sort == 2) $sqlSort .= "ddn ASC, duedate ASC, prio DESC, ow ASC";	// byDueDate
	elseif($sort == 102) $sqlSort .= "ddn DESC, duedate DESC, prio ASC, ow DESC";// byDueDate (reverse)
	elseif($sort == 3) $sqlSort .= "d_created ASC, prio DESC, ow ASC";			// byDateCreated
	elseif($sort == 103) $sqlSort .= "d_created DESC, prio ASC, ow DESC";		// byDateCreated (reverse)
	elseif($sort == 4) $sqlSort .= "d_edited ASC, prio DESC, ow ASC";			// byDateModified
	elseif($sort == 104) $sqlSort .= "d_edited DESC, prio ASC, ow DESC";		// byDateModified (reverse)
	else $sqlSort .= "ow ASC";

	$t = array();
	$t['total'] = 0;
	$t['list'] = array();
	$q = $db->dq("SELECT *, duedate IS NULL AS ddn FROM {$db->prefix}todolist $inner WHERE 1=1 $sqlWhere $sqlSort");
	while($r = $q->fetch_assoc($q))
	{
		$t['total']++;
		$t['list'][] = prepareTaskRow($r);
	}
	if($setCompl && have_write_access($listId)) {
		$bitwise = ($compl == 0) ? 'taskview & ~1' : 'taskview | 1';
		$db->dq("UPDATE {$db->prefix}lists SET taskview=$bitwise WHERE id=$listId");
	}

	if(($setNotification === '0' || $setNotification === '1') && have_write_access($listId)) {
		if($setNotification == 1) {
			NotificationListener::enableNotification(NotificationListener::LISTENER_TYPE_LIST, $listId);
		} else {
			NotificationListener::disableNotification(NotificationListener::LISTENER_TYPE_LIST, $listId);
		}
	}
	return $t;
}

function prepareTaskRow($r)
{
	$lang = Lang::instance();
	$dueA = prepare_duedate($r['duedate']);
	$formatCreatedInline = $formatCompletedInline = Config::get('dateformatshort');
	if(date('Y') != date('Y',$r['d_created'])) $formatCreatedInline = Config::get('dateformat2');
	if($r['d_completed'] && date('Y') != date('Y',$r['d_completed'])) $formatCompletedInline = Config::get('dateformat2');

	$dCreated = timestampToDatetime($r['d_created']);
	$dCompleted = $r['d_completed'] ? timestampToDatetime($r['d_completed']) : '';

	$db = DBConnection::instance();
	$current_user_id = (Config::get('multiuser') == 1)?(int)$_SESSION['userid']:0;
	$notification_id = (int)$db->sq("SELECT id FROM {$db->prefix}notification_listeners WHERE type = 'list' AND value = ".$r['id']." AND user_id=".$current_user_id);

	$progress = '';
	$progress_current = '';
	$progress_total = '';
	if(!empty($r['duration'])) {
		$task_total = TimeTracker::getTaskTotal($r['id']);
		if($task_total > 0) {
			$progress = ceil(($task_total*100)/($r['duration']*60));
			$progress_current = $task_total/60;
			$progress_total = $r['duration'];
		}
	}

	if(!empty($r['duration']) && floatval($r['duration']) > 0) {
		$duration = floatval($r['duration']);
		$duration_h = floor($duration);
		$duration_m = round(($duration - floor($duration)) * 60,0);
	} else {
		$duration_h = '';
		$duration_m = '';
	}

	return array(
		'id' => $r['id'],
		'title' => escapeTags($r['title']),
		'listId' => $r['list_id'],
		'date' => htmlarray($dCreated),
		'dateInt' => (int)$r['d_created'],
		'dateInline' => htmlarray(formatTime($formatCreatedInline, $r['d_created'])),
		'dateInlineTitle' => htmlarray(sprintf($lang->get('taskdate_inline_created'), $dCreated)),
		'dateEditedInt' => (int)$r['d_edited'],
		'dateCompleted' => htmlarray($dCompleted),
		'dateCompletedInline' => $r['d_completed'] ? htmlarray(formatTime($formatCompletedInline, $r['d_completed'])) : '',
		'dateCompletedInlineTitle' => htmlarray(sprintf($lang->get('taskdate_inline_completed'), $dCompleted)),
		'compl' => (int)$r['compl'],
		'notification' => ($notification_id > 0)?1:0,
		'prio' => $r['prio'],
		'note' => nl2br(escapeTags($r['note'])),
		'noteText' => (string)$r['note'],
		'ow' => (int)$r['ow'],
		'tags' => htmlarray($r['tags']),
		'tags_ids' => htmlarray($r['tags_ids']),
		'duedate' => $dueA['formatted'],
		'duedate_h' => (strtotime($r['duedate']))?intval(date("H", strtotime($r['duedate']))):0,
		'duedate_m' => (strtotime($r['duedate']))?intval(date("i", strtotime($r['duedate']))):0,
		'dueClass' => $dueA['class'],
		'dueStr' => htmlarray($r['compl'] && $dueA['timestamp'] ? formatTime($formatCompletedInline, $dueA['timestamp']) : $dueA['str']),
		'dueInt' => date2int($r['duedate']),
		'dueTitle' => htmlarray(sprintf($lang->get('taskdate_inline_duedate'), $dueA['formatted'])),
		'duration_h' => $duration_h,
		'duration_m' => $duration_m,
		'progress' => $progress,
		'progress_current' => $progress_current,
		'progress_total' => $progress_total,
		'comments' => getTaskComments($r['id']),
		'author' => ($r['author'] > 0 && Config::get('multiuser') == 1)?getUserName($r['author']):'null'
	);
}

function getTaskComments($task_id) {
	$db = DBConnection::instance();
	$q = $db->dq("SELECT * FROM {$db->prefix}comments WHERE task_id = $task_id");

	$result = array();
	while($r = $q->fetch_assoc()) {
		$new_item = array();
		$new_item['user'] = getUserName($r['user_id']);
		$new_item['date'] = date(Config::get('dateformat').' H:i', strtotime($r['created']));
		$new_item['comment'] = $r['comment'];
		$result[] = $new_item;
	}
	return $result;
}

function check_read_access($listId = null)
{
	$db = DBConnection::instance();
	if(Config::get('password') == '') return true;
	if(is_logged()) return true;
	if($listId !== null)
	{
		$id = $db->sq("SELECT id FROM {$db->prefix}lists WHERE id=? AND published=1", array($listId));
		if($id) return true;
	}
	jsonExit( array('total'=>0, 'list'=>array(), 'denied'=>1) );
	return false;
}

function have_write_access($listId = null)
{
	if(is_readonly()) return false;
	// check list exist
	if($listId !== null)
	{
		$db = DBConnection::instance();
		$count = $db->sq("SELECT COUNT(*) FROM {$db->prefix}lists WHERE id=?", array($listId));
		if(!$count) return false;
	}
	return true;
}

function check_write_access($listId = null)
{
	if(have_write_access($listId)) return;
	jsonExit( array('total'=>0, 'list'=>array(), 'denied'=>1) );
}

function check_admin_access()
{
	if(!is_admin()) {
		jsonExit( array('total'=>0, 'list'=>array(), 'denied'=>1) );
	}
}

function inputTaskParams()
{
	$a = array(
		'id' => _post('id'),
		'title'=> trim(_post('title')),
		'note' => str_replace("\r\n", "\n", trim(_post('note'))),
		'prio' => (int)_post('prio'),
		'duedate' => '',
		'tags' => trim(_post('tags')),
		'listId' => (int)_post('list'),

	);
	if($a['prio'] < -1) $a['prio'] = -1;
	elseif($a['prio'] > 2) $a['prio'] = 2;
	return $a;
}

function prepareTags($tagsStr)
{
	$tags = explode(',', $tagsStr);
	if(!$tags) return 0;

	$aTags = array('tags'=>array(), 'ids'=>array());
	foreach($tags as $tag)
	{
		$tag = str_replace(array('"',"'",'<','>','&','/','\\','^'),'',trim($tag));
		if($tag == '') continue;

		$aTag = getOrCreateTag($tag);
		if($aTag && !in_array($aTag['id'], $aTags['ids'])) {
			$aTags['tags'][] = $aTag['name'];
			$aTags['ids'][] = $aTag['id'];
		}
	}
	return $aTags;
}

function getOrCreateTag($name)
{
	$db = DBConnection::instance();
	$tagId = $db->sq("SELECT id FROM {$db->prefix}tags WHERE name=?", array($name));
	if($tagId) return array('id'=>$tagId, 'name'=>$name);

	$db->ex("INSERT INTO {$db->prefix}tags (name) VALUES (?)", array($name));
	return array('id'=>$db->last_insert_id($db->prefix.'tags'), 'name'=>$name);
}

function getTagId($tag)
{
	$db = DBConnection::instance();
	$id = $db->sq("SELECT id FROM {$db->prefix}tags WHERE name=?", array($tag));
	return $id ? $id : 0;
}

function get_task_tags($id)
{
	$db = DBConnection::instance();
	$q = $db->dq("SELECT tag_id FROM {$db->prefix}tag2task WHERE task_id=?", $id);
	$a = array();
	while($r = $q->fetch_row()) {
		$a[] = $r[0];
	}
	return $a;
}


function addTaskTags($taskId, $tagIds, $listId)
{
	$db = DBConnection::instance();
	if(!$tagIds) return;
	foreach($tagIds as $tagId)
	{
		$db->ex("INSERT INTO {$db->prefix}tag2task (task_id,tag_id,list_id) VALUES (?,?,?)", array($taskId,$tagId,$listId));
	}
}

function parse_smartsyntax($title)
{
	$a = array();
	if(!preg_match("|^(/([+-]{0,1}\d+)?/)?(.*?)(\s+/([^/]*)/$)?$|", $title, $m)) return false;
	$a['prio'] = isset($m[2]) ? (int)$m[2] : 0;
	$a['title'] = isset($m[3]) ? trim($m[3]) : '';
	$a['tags'] = isset($m[5]) ? trim($m[5]) : '';
	if($a['prio'] < -1) $a['prio'] = -1;
	elseif($a['prio'] > 2) $a['prio'] = 2;
	return $a;
}

function tag_size($qmin, $q, $step)
{
	if($step == 0) return 1;
	$v = ceil(($q - $qmin)/$step);
	if($v == 0) return 0;
	else return $v-1;

}

function parse_duedate($s)
{
	$df2 = Config::get('dateformat2');
	if(max((int)strpos($df2,'n'), (int)strpos($df2,'m')) > max((int)strpos($df2,'d'), (int)strpos($df2,'j'))) $formatDayFirst = true;
	else $formatDayFirst = false;

	$y = $m = $d = 0;
	if(preg_match("|^(\d+)-(\d+)-(\d+)\b|", $s, $ma)) {
		$y = (int)$ma[1]; $m = (int)$ma[2]; $d = (int)$ma[3];
	}
	elseif(preg_match("|^(\d+)\/(\d+)\/(\d+)\b|", $s, $ma))
	{
		if($formatDayFirst) {
			$d = (int)$ma[1]; $m = (int)$ma[2]; $y = (int)$ma[3];
		} else {
			$m = (int)$ma[1]; $d = (int)$ma[2]; $y = (int)$ma[3];
		}
	}
	elseif(preg_match("|^(\d+)\.(\d+)\.(\d+)\b|", $s, $ma)) {
		$d = (int)$ma[1]; $m = (int)$ma[2]; $y = (int)$ma[3];
	}
	elseif(preg_match("|^(\d+)\.(\d+)\b|", $s, $ma)) {
		$d = (int)$ma[1]; $m = (int)$ma[2]; 
		$a = explode(',', date('Y,m,d'));
		if( $m<(int)$a[1] || ($m==(int)$a[1] && $d<(int)$a[2]) ) $y = (int)$a[0]+1; 
		else $y = (int)$a[0];
	}
	elseif(preg_match("|^(\d+)\/(\d+)\b|", $s, $ma))
	{
		if($formatDayFirst) {
			$d = (int)$ma[1]; $m = (int)$ma[2];
		} else {
			$m = (int)$ma[1]; $d = (int)$ma[2];
		}
		$a = explode(',', date('Y,m,d'));
		if( $m<(int)$a[1] || ($m==(int)$a[1] && $d<(int)$a[2]) ) $y = (int)$a[0]+1; 
		else $y = (int)$a[0];
	}
	else return null;
	if($y < 100) $y = 2000 + $y;
	elseif($y < 1000 || $y > 2099) $y = 2000 + (int)substr((string)$y, -2);
	if($m > 12) $m = 12;
	$maxdays = daysInMonth($m,$y);
	if($m < 10) $m = '0'.$m;
	if($d > $maxdays) $d = $maxdays;
	elseif($d < 10) $d = '0'.$d;
	return "$y-$m-$d";
}

function prepare_duedate($duedate)
{
	$lang = Lang::instance();

	$a = array( 'class'=>'', 'str'=>'', 'formatted'=>'', 'timestamp'=>0 );
	if($duedate == '') {
		return $a;
	}
	//$ad = explode('-', $duedate);
	$ad = strtotime($duedate);
	$ad = array(date("Y",$ad),date("m",$ad),date("d",$ad));
	$at = explode('-', date('Y-m-d'));
	$a['timestamp'] = mktime(0,0,0,$ad[1],$ad[2],$ad[0]);
	$diff = mktime(0,0,0,$ad[1],$ad[2],$ad[0]) - mktime(0,0,0,$at[1],$at[2],$at[0]);

	if($diff < -604800 && $ad[0] == $at[0])	{ $a['class'] = 'past'; $a['str'] = formatDate3(Config::get('dateformatshort'), (int)$ad[0], (int)$ad[1], (int)$ad[2], $lang); }
	elseif($diff < -604800)	{ $a['class'] = 'past'; $a['str'] = formatDate3(Config::get('dateformat2'), (int)$ad[0], (int)$ad[1], (int)$ad[2], $lang); }
	elseif($diff < -86400)		{ $a['class'] = 'past'; $a['str'] = sprintf($lang->get('daysago'),ceil(abs($diff)/86400)); }
	elseif($diff < 0)			{ $a['class'] = 'past'; $a['str'] = $lang->get('yesterday'); }
	elseif($diff < 86400)		{ $a['class'] = 'today'; $a['str'] = $lang->get('today'); }
	elseif($diff < 172800)		{ $a['class'] = 'today'; $a['str'] = $lang->get('tomorrow'); }
	elseif($diff < 691200)		{ $a['class'] = 'soon'; $a['str'] = sprintf($lang->get('indays'),ceil($diff/86400)); }
	elseif($ad[0] == $at[0])	{ $a['class'] = 'future'; $a['str'] = formatDate3(Config::get('dateformatshort'), (int)$ad[0], (int)$ad[1], (int)$ad[2], $lang); }
	else						{ $a['class'] = 'future'; $a['str'] = formatDate3(Config::get('dateformat2'), (int)$ad[0], (int)$ad[1], (int)$ad[2], $lang); }

	if(date("H:i", strtotime($duedate)) != '00:00') {
		$a['str'] .= ' - '.date("H:i", strtotime($duedate));
	}

	$a['formatted'] = formatTime(Config::get('dateformat2'), $a['timestamp']);

	return $a;
}

function date2int($d)
{
	if(!$d) return 33330000;
	return date("YmdHi", strtotime($d));
	/*$ad = explode('-', $d);
	$s = $ad[0];
	if(strlen($ad[1]) < 2) $s .= "0$ad[1]"; else $s .= $ad[1];
	if(strlen($ad[2]) < 2) $s .= "0$ad[2]"; else $s .= $ad[2];
	return (int)$s;*/
}

function daysInMonth($m, $y=0)
{
	if($y == 0) $y = (int)date('Y');
	$a = array(1=>31,(($y-2000)%4?28:29),31,30,31,30,31,31,30,31,30,31);
	if(isset($a[$m])) return $a[$m]; else return 0;
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	if($errno==E_ERROR || $errno==E_CORE_ERROR || $errno==E_COMPILE_ERROR || $errno==E_USER_ERROR || $errno==E_PARSE) $error = 'Error';
	elseif($errno==E_WARNING || $errno==E_CORE_WARNING || $errno==E_COMPILE_WARNING || $errno==E_USER_WARNING || $errno==E_STRICT) {
		if(error_reporting() & $errno) $error = 'Warning'; else return;
	}
	elseif($errno==E_NOTICE || $errno==E_USER_NOTICE) {
		if(error_reporting() & $errno) $error = 'Notice'; else return;
	}
	elseif(defined('E_DEPRECATED') && ($errno==E_DEPRECATED || $errno==E_USER_DEPRECATED)) { # since 5.3.0
		if(error_reporting() & $errno) $error = 'Notice'; else return;
	}
	else $error = "Error ($errno)";	# here may be E_RECOVERABLE_ERROR
	throw new Exception("$error: '$errstr' in $errfile:$errline", -1);
}

function myExceptionHandler(Exception $e)
{
	if(-1 == $e->getCode()) {
		echo $e->getMessage()."\n". $e->getTraceAsString();
		exit;
	}
	echo 'Exception: \''. $e->getMessage() .'\' in '. $e->getFile() .':'. $e->getLine(); //."\n". $e->getTraceAsString();
	exit;
}

/**
 * @param $id
 * @return mixed
 * @todo delete unused tags?
 */
function deleteTask($id)
{
	check_write_access();
	$db = DBConnection::instance();

	$title = $db->sq("SELECT title FROM {$db->prefix}todolist WHERE id=$id");
	$list_id = $db->sq("SELECT list_id FROM {$db->prefix}todolist WHERE id=$id");

	$db->ex("BEGIN");
	$db->ex("DELETE FROM {$db->prefix}tag2task WHERE task_id=$id");
	$db->ex("DELETE FROM {$db->prefix}time_tracker WHERE task_id=$id");
	$db->dq("DELETE FROM {$db->prefix}todolist WHERE id=$id");
	$affected = $db->affected();
	$db->ex("COMMIT");

	addNotification(_r('n_task_deleted', $title), Notification::NOTIFICATION_TYPE_TASK_CHANGED, $list_id, $id);

	return $affected;
}

function moveTask($id, $listId)
{
	check_write_access();
	$db = DBConnection::instance();

	// Check task exists and not in target list
	$r = $db->sqa("SELECT * FROM {$db->prefix}todolist WHERE id=?", array($id));
	if(!$r || $listId == $r['list_id']) return false;

	// Check target list exists
	if(!$db->sq("SELECT COUNT(*) FROM {$db->prefix}lists WHERE id=?", $listId))
		return false;

	$ow = 1 + (int)$db->sq("SELECT MAX(ow) FROM {$db->prefix}todolist WHERE list_id=? AND compl=?", array($listId, $r['compl']?1:0));
	
	$db->ex("BEGIN");
	$db->ex("UPDATE {$db->prefix}tag2task SET list_id=? WHERE task_id=?", array($listId, $id));
	$db->dq("UPDATE {$db->prefix}todolist SET list_id=?, ow=?, d_edited=? WHERE id=?", array($listId, $ow, time(), $id));
	$db->ex("COMMIT");
	return true;
}

function prepareList($row)
{
	$taskview = (int)$row['taskview'];

	$db = DBConnection::instance();
	$current_user_id = (Config::get('multiuser') == 1)?intval($_SESSION['userid']):0;
	$notification_id = (int)$db->sq("SELECT id FROM {$db->prefix}notification_listeners WHERE type = 'list' AND value = ".$row['id']." AND user_id=".$current_user_id);

	return array(
		'id' => $row['id'],
		'name' => htmlarray($row['name']),
		'sort' => (int)$row['sorting'],
		'published' => $row['published'] ? 1 :0,
		'showCompl' => $taskview & 1 ? 1 : 0,
		'showNotes' => $taskview & 2 ? 1 : 0,
		'hidden' => $taskview & 4 ? 1 : 0,
		'notification' => $notification_id > 0 ? 1 : 0,
	);
}

function getUserListsSimple()
{
	$db = DBConnection::instance();
	$a = array();
	$q = $db->dq("SELECT id,name FROM {$db->prefix}lists ORDER BY id ASC");
	while($r = $q->fetch_row()) {
		$a[$r[0]] = $r[1];
	}
	return $a;
}

/**
 * @param $text string
 * @param $type string Notification type
 * @param $list_id integer|null
 * @param $task_id integer|null
 */
function addNotification($text, $type, $list_id = null, $task_id = null) {
	// only add Notifications if we are on multi user mode
	if(Config::get('multiuser') == 1) {
		try {
			Notification::add($text, $type, $list_id, $task_id);
		} catch(Exception $e) {
			var_dump($e->getMessage());
		}
	}
}
