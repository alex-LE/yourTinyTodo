<?php

class NotificationListener {
	/**
	 * will only receive notifications about the tasks of a specific list
	 */
	const LISTENER_TYPE_TASK = 'task';

	/**
	 * will receive all notifications about the tasks of a specific list and about the list itself
	 */
	const LISTENER_TYPE_LIST = 'list';

	/**
	 * will get all notifications
	 */
	const LISTENER_TYPE_GLOBAL = 'global';

	/**
	 * @var $userid integer
	 */
	private $userid;

	/**
	 * @var $type string LISTENER_TYPE_TASK|LISTENER_TYPE_LIST|LISTENER_TYPE_GLOBAL
	 */
	private $type;

	/**
	 * @desc value for the listener, can be any id, like a list id
	 * @var $value integer
	 */
	private $value;

	public static function enableNotification($type, $value = null, $take_care_of_global = true, $user_id = null) {
		$db = DBConnection::instance();
		$current_user_id = (empty($user_id))?(int)$_SESSION['userid']:$user_id;
		if(!in_array($type, array(NotificationListener::LISTENER_TYPE_GLOBAL,NotificationListener::LISTENER_TYPE_LIST,NotificationListener::LISTENER_TYPE_TASK))) {
			return false;
		}

		if($type != NotificationListener::LISTENER_TYPE_GLOBAL && empty($value)) {
			return false;
		}

		if($take_care_of_global && NotificationListener::hasGlobalNotifications($current_user_id)) {
			return true;
		}

		if(empty($value)) {
			$db->dq("DELETE FROM {$db->prefix}notification_listeners WHERE type = '".$type."' AND user_id = ".$current_user_id."");
			$db->dq("INSERT INTO {$db->prefix}notification_listeners (user_id, type) VALUES (?, ?)", array($current_user_id, $type));
		} else {
			$db->dq("DELETE FROM {$db->prefix}notification_listeners WHERE type = '".$type."' AND user_id = ".$current_user_id." AND value=$value");
			$db->dq("INSERT INTO {$db->prefix}notification_listeners (user_id, type, value) VALUES (?, ?, ?)", array($current_user_id, $type, $value));
		}
	}

	public static function disableNotification($type, $value = null, $user_id = null) {
		if($type != NotificationListener::LISTENER_TYPE_GLOBAL && empty($value)) {
			return false;
		}

		$db = DBConnection::instance();
		$current_user_id = (empty($user_id))?(int)$_SESSION['userid']:$user_id;
		if(!in_array($type, array(NotificationListener::LISTENER_TYPE_GLOBAL,NotificationListener::LISTENER_TYPE_LIST,NotificationListener::LISTENER_TYPE_TASK))) {
			return false;
		}

		if(empty($value)) {
			$db->dq("DELETE FROM {$db->prefix}notification_listeners WHERE type = '".$type."' AND user_id = ".$current_user_id."");
		} else {
			$db->dq("DELETE FROM {$db->prefix}notification_listeners WHERE type = '".$type."' AND user_id = ".$current_user_id." AND value=$value");
		}
	}

	public static function hasGlobalNotifications($userid) {
		if(empty($userid)) {
			return 0;
		}
		$db = DBConnection::instance();
		return $db->sq("SELECT COUNT(*) FROM {$db->prefix}notification_listeners WHERE type = '".NotificationListener::LISTENER_TYPE_GLOBAL."' AND user_id = ".$userid) > 0;
	}

	public function setUserid($userid)
	{
		$this->userid = $userid;
	}

	public function getUserid()
	{
		return $this->userid;
	}

	/**
	 * @param string LISTENER_TYPE_TASK|LISTENER_TYPE_LIST|LISTENER_TYPE_GLOBAL
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param int $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return int
	 */
	public function getValue()
	{
		return $this->value;
	}
}

class NoticficationListener_List {

	/**
	 * @static
	 * @param $type
	 * @param array $value
	 * @return array
	 * @throws Exception
	 */
	public static function findByListenerTypeAndValue($type,$value = null) {
		if($type !== NotificationListener::LISTENER_TYPE_GLOBAL && empty($value)) {
			throw new Exception('Can not find non-global listener ('.$type.') without a list or task id ('.$value.')');
		}

		$return = array();
		$db = DBConnection::instance();

		if($type === NotificationListener::LISTENER_TYPE_GLOBAL) {
			$result = $db->dq("SELECT * FROM {$db->prefix}notification_listeners WHERE type = 'global'");
		} else {
			$result = $db->dq("SELECT * FROM {$db->prefix}notification_listeners WHERE type = ? AND value = ?", array($type, $value));
		}

		while($row = $result->fetch_assoc()) {
			$item = new NotificationListener();
			$item->setType($row['type']);
			$item->setUserid($row['user_id']);
			if($type !== NotificationListener::LISTENER_TYPE_GLOBAL) {
				$item->setValue($row['value']);
			}
			$return[] = $item;
		}
		return $return;
	}
}