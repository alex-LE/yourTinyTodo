<?

class TimeTracker {

	/**
	 * @static
	 * @param $taskid integer
	 * @param $time	integer
	 */
	public static function trackTime($taskid, $time, $date = null) {
		$current_user_id = (int)$_SESSION['userid'];
		$db = DBConnection::instance();

		if(empty($date) || $date == 'today') {
			$date = date("Y-m-d H:i");
		} else {
			$date = date("Y-m-d 00:00",strtotime($date));
		}

		$db->dq("INSERT INTO {$db->prefix}time_tracker (task_id, user_id, minutes, created) VALUES (?, ?, ?, ?)", array($taskid, $current_user_id, $time, $date));
	}

	public static function getTaskTotal($taskid) {
		if(intval($taskid) <= 0) {
			return 0;
		}
		$db = DBConnection::instance();
		return $db->sq("SELECT SUM(minutes) FROM {$db->prefix}time_tracker WHERE task_id = ".intval($taskid));
	}
}