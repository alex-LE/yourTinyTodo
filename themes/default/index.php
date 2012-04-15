<?php header("Content-type: text/html; charset=utf-8"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php mttinfo('title'); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php mttinfo('template_url'); ?>style.css?v=<?=MTT_VERSION?>" media="all" />
	<?php if(Config::get('rtl')): ?>
	<link rel="stylesheet" type="text/css" href="<?php mttinfo('template_url'); ?>style_rtl.css?v=<?=MTT_VERSION?>" media="all" />
	<?php endif; ?>
	<?php if(isset($_GET['pda'])): ?>
	<meta name="viewport" id="viewport" content="width=device-width" />
	<link rel="stylesheet" type="text/css" href="<?php mttinfo('template_url'); ?>pda.css?v=<?=MTT_VERSION?>" media="all" />
	<?php else: ?>
	<link rel="stylesheet" type="text/css" href="<?php mttinfo('template_url'); ?>print.css?v=<?=MTT_VERSION?>" media="print" />
	<?php endif; ?>
</head>

<body>

<script type="text/javascript" src="<?php mttinfo('mtt_url'); ?>jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php mttinfo('mtt_url'); ?>jquery/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php mttinfo('mtt_url'); ?>jquery/jquery.autocomplete-1.1.js"></script>
<script type="text/javascript" src="<?php mttinfo('mtt_url'); ?>mytinytodo.js?v=<?=MTT_VERSION?>"></script>
<script type="text/javascript" src="<?php mttinfo('mtt_url'); ?>mytinytodo_lang.php?v=<?=MTT_VERSION?>"></script>
<script type="text/javascript" src="<?php mttinfo('mtt_url'); ?>mytinytodo_ajax_storage.js?v=<?=MTT_VERSION?>"></script>

<script type="text/javascript">
	$().ready(function(){

	<?php if(isset($_GET['pda'])): ?>

		$('body').width(screen.width);
		$(window).resize(function() {
			$('body').width(screen.width);
		});

		<?php endif; ?>

		mytinytodo.mttUrl = "<?php mttinfo('mtt_url'); ?>";
		mytinytodo.templateUrl = "<?php mttinfo('template_url'); ?>";
		mytinytodo.db = new mytinytodoStorageAjax(mytinytodo);
		mytinytodo.init({
			needAuth: <?php echo $needAuth ? "true" : "false"; ?>,
			multiUser: <?php echo $multiUser ? "true" : "false"; ?>,
			admin: <?php echo is_admin() ? "true" : "false"; ?>,
			readOnly: <?php echo is_readonly() ? "true" : "false"; ?>,
			userId: <?php echo (!empty($_SESSION['userid']))?$_SESSION['userid']:'null'; ?>,
			userRole: <?php echo (!empty($_SESSION['role']))?$_SESSION['role']:'null'; ?>,
			isLogged: <?php echo ($needAuth && is_logged()) ? "true" : "false"; ?>,
			showdate: <?php echo (Config::get('showdate') && !isset($_GET['pda'])) ? "true" : "false"; ?>,
			singletab: <?php echo (isset($_GET['singletab']) || isset($_GET['pda'])) ? "true" : "false"; ?>,
			duedatepickerformat: "<?php echo htmlspecialchars(Config::get('dateformat2')); ?>",
			firstdayofweek: <?php echo (int) Config::get('firstdayofweek'); ?>,
			autotag: <?php echo Config::get('autotag') ? "true" : "false"; ?>
		<?php if(isset($_GET['list'])) echo ",openList: ". (int)$_GET['list']; ?>
		<?php if(isset($_GET['pda'])) echo ", touchDevice: true"; ?>
		}).loadLists(1);
	});
</script>

<div id="wrapper">
<div id="container">
<div id="mtt_body">

<h2><?php mttinfo('title'); ?></h2>

<div id="loading"></div>

<div id="bar">
	<div id="msg"><span class="msg-text"></span><div class="msg-details"></div></div>
	<div class="bar-menu">
 <span class="menu-owner" style="display:none">
   <a href="#settings" id="settings"><?php _e('a_settings');?></a>
 </span>
		<span class="bar-delim" style="display:none"> | </span>
 <span id="bar_auth">
  <span id="bar_public" style="display:none"><?php _e('public_tasks');?> |</span>
  <a href="#login" id="bar_login" class="nodecor"><u><?php _e('a_login');?></u> <span class="arrdown"></span></a>
  <a href="#logout" id="bar_logout"><?php _e('a_logout');?></a>
 </span>
	</div>
</div>

<br clear="all" />

<div id="page_tasks" style="display:none">

	<div id="lists">
		<ul class="mtt-tabs"></ul>
		<div class="mtt-tabs-add-button" title="<?php _e('list_new'); ?>"><span></span></div>
		<div id="tabs_buttons">
			<div class="mtt-tabs-select-button mtt-tabs-button" title="<?php _e('list_select'); ?>"><span></span></div>
		</div>
		<div id="list_all" class="mtt-tab mtt-tabs-alltasks mtt-tabs-hidden"><a href="#alltasks"><span><?php _e('alltasks'); ?></span></a><div class="list-action"></div></div>
	</div>



	<div id="toolbar" class="mtt-htabs">

		<div id="htab_search">
			<table class="mtt-searchbox"><tr><td>
				<div class="mtt-searchbox-c">
					<input type="text" name="search" value="" maxlength="250" id="search" autocomplete="off" />
					<div class="mtt-searchbox-icon mtt-icon-search"></div>
					<div id="search_close" class="mtt-searchbox-icon mtt-icon-cancelsearch"></div>
				</div>
			</td></tr></table>
		</div>

		<div id="htab_newtask">
			<table class="mtt-taskbox"><tr><td class="mtt-tb-cell">
				<div class="mtt-tb-c">
					<form id="newtask_form" method="post" action="">
						<label id="task_placeholder" class="placeholding" for="task">
							<input type="text" name="task" value="" maxlength="250" id="task" autocomplete="off" />
							<span><?php _e('htab_newtask');?></span>
						</label>
						<div id="newtask_submit" class="mtt-taskbox-icon mtt-icon-submittask" title="<?php _e('btn_add');?>"></div>
					</form>
				</div>
			</td>
				<td><a href="#" id="newtask_adv" class="mtt-img-button" title="<?php _e('advanced_add');?>"><span></span></a></td>
			</tr></table>
		</div>

		<div id="searchbar" style="display:none"><?php _e('searching');?> <span id="searchbarkeyword"></span></div>

		<div style="clear:both"></div>

	</div>


	<h3>
		<span id="taskview" class="mtt-menu-button"><span class="btnstr"><?php _e('tasks');?></span> (<span id="total">0</span>) <span class="arrdown"></span></span>
		<span class="mtt-notes-showhide"><?php _e('notes');?> <a href="#" id="mtt-notes-show"><?php _e('notes_show');?></a> / <a href="#" id="mtt-notes-hide"><?php _e('notes_hide');?></a></span>
		<span id="mtt_filters"></span>
		<span id="tagcloudbtn" class="mtt-menu-button"><?php _e('tagcloud');?> <span class="arrdown2"></span></span>
	</h3>

	<div id="taskcontainer">
		<ol id="tasklist" class="sortable"></ol>
	</div>

</div> <!-- end of page_tasks -->


<div id="page_taskedit" style="display:none">

	<div><a href="#" class="mtt-back-button"><?php _e('go_back');?></a></div>

	<h3 class="mtt-inadd"><?php _e('add_task');?></h3>
	<h3 class="mtt-inedit"><?php _e('edit_task');?>
		<span id="taskedit-date" class="mtt-inedit">
			(<span class="date-created" title="<?php _e('taskdate_created');?>"><span>&nbsp;</span></span><span class="date-completed" title="<?php _e('taskdate_completed');?>"> &mdash; <span>&nbsp;</span></span>)
		</span>
	</h3>

	<form id="taskedit_form" name="edittask" method="post" action="">
		<input type="hidden" name="isadd" value="0" />
		<input type="hidden" name="id" value="" />
		<div class="form-row form-row-short">
			<span class="h"><?php _e('priority');?></span>
			<select name="prio">
				<option value="2">+2</option><option value="1">+1</option><option value="0" selected="selected">&plusmn;0</option><option value="-1">&minus;1</option>
			</select>
		</div>
		<div class="form-row form-row-short">
			<span class="h"><?php _e('due');?> </span>
			<input name="duedate" id="duedate" value="" class="in100" title="Y-M-D, M/D/Y, D.M.Y, M/D, D.M" autocomplete="off" />
		</div>
		<div class="form-row-short-end"></div>
		<div class="form-row"><div class="h"><?php _e('task');?></div> <input type="text" name="task" value="" class="in500" maxlength="250" /></div>
		<div class="form-row"><div class="h"><?php _e('note');?></div> <textarea name="note" class="in500"></textarea></div>
		<div class="form-row"><div class="h"><?php _e('tags');?></div>
			<table cellspacing="0" cellpadding="0" width="100%"><tr>
				<td><input type="text" name="tags" id="edittags" value="" class="in500" maxlength="250" /></td>
				<td class="alltags-cell">
					<a href="#" id="alltags_show"><?php _e('alltags_show');?></a>
					<a href="#" id="alltags_hide" style="display:none"><?php _e('alltags_hide');?></a></td>
			</tr></table>
		</div>
		<div class="form-row" id="alltags" style="display:none;"><?php _e('alltags');?> <span class="tags-list">&nbsp;</span></div>
		<div class="form-row form-bottom-buttons">
			<input type="submit" value="<?php _e('save');?>" />
			<input type="button" id="mtt_edit_cancel" class="mtt-back-button" value="<?php _e('cancel');?>" />
		</div>
	</form>

</div>  <!-- end of page_taskedit -->


<div id="authform" style="display:none">
	<form id="login_form" action="" method="post">
		<?php if($multiUser) { ?>
		<div><label for="username"><?php _e('um_username');?></label><input type="text" name="username" id="username" /></div>
		<div><label for="password"><?php _e('um_password');?></label><input type="password" name="password" id="password" /></div>
		<div><input type="submit" value="<?php _e('btn_login');?>" /></div>
		<?php } else { ?>
		<div class="h"><?php _e('password');?></div>
		<div><input type="password" name="password" id="password" /></div>
		<div><input type="submit" value="<?php _e('btn_login');?>" /></div>
		<?php } ?>
	</form>
</div>

<div id="priopopup" style="display:none">
	<span class="prio-neg prio-neg-1">&minus;1</span>
	<span class="prio-zero">&plusmn;0</span>
	<span class="prio-pos prio-pos-1">+1</span>
	<span class="prio-pos prio-pos-2">+2</span>
</div>

<div id="taskviewcontainer" class="mtt-menu-container" style="display:none">
	<ul>
		<li id="view_tasks"><?php _e('tasks');?> (<span id="cnt_total">0</span>)</li>
		<li id="view_past"><?php _e('f_past');?> (<span id="cnt_past">0</span>)</li>
		<li id="view_today"><?php _e('f_today');?> (<span id="cnt_today">0</span>)</li>
		<li id="view_soon"><?php _e('f_soon');?> (<span id="cnt_soon">0</span>)</li>
	</ul>
</div>

<div id="tagcloud" style="display:none">
	<a id="tagcloudcancel" class="mtt-img-button"><span>&nbsp;</span></a>
	<div id="tagcloudload"></div>
	<div id="tagcloudcontent"></div>
</div>

<?php
	$show_edit_options = (!isset($_SESSION['role']) || $_SESSION['role'] < 3);
?>

<div id="listmenucontainer" class="mtt-menu-container" style="display:none">
	<ul>
		<?php if($show_edit_options) {?><li class="mtt-need-list mtt-need-real-list" id="btnRenameList"><?php _e('list_rename');?></li><?}?>
		<?php if($show_edit_options) {?><li class="mtt-need-list mtt-need-real-list" id="btnDeleteList"><?php _e('list_delete');?></li><?}?>
		<?php if($show_edit_options) {?><li class="mtt-need-list mtt-need-real-list" id="btnClearCompleted"><?php _e('list_clearcompleted');?></li><?}?>
		<?php if($show_edit_options) {?><li class="mtt-need-list mtt-need-real-list mtt-menu-indicator" submenu="listexportmenucontainer"><div class="submenu-icon"></div><?php _e('list_export'); ?></li><?}?>
		<?php if($show_edit_options) {?><li class="mtt-menu-delimiter mtt-need-real-list"></li><?}?>
		<?php if($show_edit_options) {?><li class="mtt-need-list mtt-need-real-list" id="btnPublish"><div class="menu-icon"></div><?php _e('list_publish');?></li><?}?>
		<li class="mtt-need-list mtt-need-real-list" id="btnRssFeed"><div class="menu-icon"></div><?php _e('list_rssfeed');?></li>
		<li class="mtt-menu-delimiter mtt-need-real-list"></li>
		<li class="mtt-need-list mtt-need-real-list sort-item" id="sortByHand"><div class="menu-icon"></div><?php _e('sortByHand');?> <span class="mtt-sort-direction">&nbsp;</span></li>
		<li class="mtt-need-list sort-item" id="sortByDateCreated"><div class="menu-icon"></div><?php _e('sortByDateCreated');?> <span class="mtt-sort-direction">&nbsp;</span></li>
		<li class="mtt-need-list sort-item" id="sortByPrio"><div class="menu-icon"></div><?php _e('sortByPriority');?> <span class="mtt-sort-direction">&nbsp;</span></li>
		<li class="mtt-need-list sort-item" id="sortByDueDate"><div class="menu-icon"></div><?php _e('sortByDueDate');?> <span class="mtt-sort-direction">&nbsp;</span></li>
		<li class="mtt-need-list sort-item" id="sortByDateModified"><div class="menu-icon"></div><?php _e('sortByDateModified');?> <span class="mtt-sort-direction">&nbsp;</span></li>
		<li class="mtt-menu-delimiter"></li>
		<li class="mtt-need-list" id="btnShowCompleted"><div class="menu-icon"></div><?php _e('list_showcompleted');?></li>
	</ul>
</div>

<div id="listexportmenucontainer" class="mtt-menu-container" style="display:none">
	<ul>
		<li class="mtt-need-list mtt-need-real-list" id="btnExportCSV"><?php _e('list_export_csv');?></li>
		<li class="mtt-need-list mtt-need-real-list" id="btnExportICAL"><?php _e('list_export_ical');?></li>
	</ul>
</div>

<div id="taskcontextcontainer" class="mtt-menu-container" style="display:none">
	<ul>
		<li id="cmenu_edit"><b><?php _e('action_edit');?></b></li>
		<li id="cmenu_note"><?php _e('action_note');?></li>
		<li id="cmenu_prio" class="mtt-menu-indicator" submenu="cmenupriocontainer"><div class="submenu-icon"></div><?php _e('action_priority');?></li>
		<li id="cmenu_move" class="mtt-menu-indicator" submenu="cmenulistscontainer"><div class="submenu-icon"></div><?php _e('action_move');?></li>
		<li id="cmenu_delete"><?php _e('action_delete');?></li>
	</ul>
</div>

<div id="cmenupriocontainer" class="mtt-menu-container" style="display:none">
	<ul>
		<li id="cmenu_prio:2"><div class="menu-icon"></div>+2</li>
		<li id="cmenu_prio:1"><div class="menu-icon"></div>+1</li>
		<li id="cmenu_prio:0"><div class="menu-icon"></div>&plusmn;0</li>
		<li id="cmenu_prio:-1"><div class="menu-icon"></div>&minus;1</li>
	</ul>
</div>

<div id="cmenulistscontainer" class="mtt-menu-container" style="display:none">
	<ul>
	</ul>
</div>

<div id="slmenucontainer" class="mtt-menu-container" style="display:none">
	<ul>
		<li id="slmenu_list:-1" class="list-id--1 mtt-need-list" <?php if(is_readonly()) echo 'style="display:none"' ?>><div class="menu-icon"></div><a href="#alltasks"><?php _e('alltasks'); ?></a></li>
		<li class="mtt-menu-delimiter slmenu-lists-begin mtt-need-list" <?php if(is_readonly()) echo 'style="display:none"' ?>></li>
	</ul>
</div>

<div id="createuser" style="display:none" class="mtt-menu-container">
	<form method="post" action="" name="createuserForm">
		<label for="um_username"><?php _e('um_username');?></label>
		<input type="text" name="um_username" id="um_username" value="" />

		<label for="um_email"><?php _e('um_email');?></label>
		<input type="text" name="um_email" id="um_email" value="" />

		<label for="um_password"><?php _e('um_password');?></label>
		<input type="password" name="um_password" id="um_password" value="" />

		<label for="um_role"><?php _e('um_role');?></label>
		<select name="um_role" id="um_role">
			<option value="1"><?php _e('um_rolename_1')?></option>
			<option value="2"><?php _e('um_rolename_2')?></option>
			<option value="3"><?php _e('um_rolename_3')?></option>
		</select>

		<input type="hidden" value="" id="um_userid" name="um_userid" />

		<input type="button" id="createuserSubmit" value="<?php _e('save')?>" />
	</form>
</div>

<div id="page_ajax" style="display:none"></div>

</div>
<div id="space"></div>
</div>

<div id="mtt-menu-modal" class="mtt-menu-modal"></div>

<div id="footer"><div id="footer_content">Powered by <strong><a href="http://www.mytinytodo.net/">myTinyTodo</a></strong> <?=MTT_VERSION?> </div></div>

</div>
</body>
</html>
<!-- r387 -->