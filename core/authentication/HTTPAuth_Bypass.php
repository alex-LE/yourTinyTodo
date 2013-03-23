<?php
require_once(YTTCOREPATH.'interfaces/authentication_bypass.interface.php');

class HTTPAuth_Bypass implements IAuthentication_Bypass {

	/**
	 * fills session data
	 */
	public function setSession() {
		//session_regenerate_id(1);
		$_SESSION['logged'] = 1;
		$userdata = $this->getUserFromDB($_SERVER['PHP_AUTH_USER']);
		$_SESSION['userid'] = $userdata['id'];
		$_SESSION['role'] = $userdata['role'];
	}

	private function getUserFromDB($username) {
		$db = DBConnection::instance();
		$result = $db->dq("SELECT role,id FROM {$db->prefix}users WHERE username = ?", array($username) );
		$row = $result->fetch_assoc();
		$return = array();
		$return['id'] = 0;		// default, empty user id
		$return['role'] = 3;	// default, readonly
		if($result && is_array($row) && count($row) > 0) {
			$return['id'] = $row['id'];
			$return['role'] = $row['role'];
		}
		return $return;
	}
}
