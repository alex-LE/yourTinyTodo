<?
require_once(YTTPATH. 'core/NotificationListener.class.php');

class Notification {

	const NOTIFICATION_TYPE_TASK_CREATED = 1;
	const NOTIFICATION_TYPE_TASK_CHANGED = 2;
	const NOTIFICATION_TYPE_TASK_COMPLETED = 3;
	const NOTIFICATION_TYPE_LIST_ADDED = 4;
	const NOTIFICATION_TYPE_LIST_DELETED = 5;
	const NOTIFICATION_TYPE_LIST_RENAMED = 6;

	public static function add($text, $type, $list_id = null, $task_id = null) {
		$db = DBConnection::instance();
		$current_user_id = (int)$_SESSION['userid'];

		$listeners = NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_GLOBAL);

		switch($type) {
			case Notification::NOTIFICATION_TYPE_TASK_CREATED:
				$listeners = array_merge($listeners, NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_LIST, $list_id));
				break;

			case Notification::NOTIFICATION_TYPE_TASK_CHANGED:
				$listeners = array_merge($listeners, NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_LIST, $list_id));
				$listeners = array_merge($listeners, NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_TASK, $task_id));
				break;

			case Notification::NOTIFICATION_TYPE_TASK_COMPLETED:
				$listeners = array_merge($listeners, NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_LIST, $list_id));
				$listeners = array_merge($listeners, NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_TASK, $task_id));
				break;

			case Notification::NOTIFICATION_TYPE_LIST_ADDED:
				break;

			case Notification::NOTIFICATION_TYPE_LIST_DELETED:
				$listeners = array_merge($listeners, NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_LIST, $list_id));
				break;

			case Notification::NOTIFICATION_TYPE_LIST_RENAMED:
				$listeners = array_merge($listeners, NoticficationListener_List::findByListenerTypeAndValue(NotificationListener::LISTENER_TYPE_LIST, $list_id));
				break;

			default:
				break;
		}

		$user_notified = array();

		foreach($listeners as $listener) {
			/**
			 * @var NotificationListener $listener
			 */
			if(in_array($listener->getUserid(), $user_notified) || $listener->getUserid() == $current_user_id ) {
				continue;
			}

			$db->dq("INSERT INTO {$db->prefix}notifications (user_id, text, created, shown) VALUES (?, ?, now(), 0)", array($listener->getUserid(), $text));
		}
	}

	public static function getUnreadCount() {
		if(!isset($_SESSION['userid'])) {
			return 0;
		}
		$current_user_id = (int)$_SESSION['userid'];
		$db = DBConnection::instance();
		return $db->sq("SELECT COUNT(*) FROM {$db->prefix}notifications WHERE shown != 1 AND user_id = ".$current_user_id);
	}
}