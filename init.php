<?php
/*
This file is part of yourTinyTodo by the yourTinyTodo community.
Copyrights for portions of this file are retained by their owners.

Based on myTinyTodo by Max Pozdeev
(C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/

if(!defined('YTTPATH')) define('YTTPATH', dirname(__FILE__) .'/');

require_once(YTTPATH. 'common.php');
require_once(YTTPATH. 'db/config.php');

ini_set('display_errors', 0);

define('YTT_VERSION', '1.5');

define('DEBUG_MODE', true);

if(!isset($config)) global $config;
Config::loadConfig($config);
unset($config);

date_default_timezone_set(Config::get('timezone'));

# MySQL Database Connection
if(Config::get('db') == 'mysql')
{
	try
	{
		require_once(YTTPATH. 'class.db.mysql.php');
		$db = DBConnection::init(new Database_Mysql);
		$db->connect(Config::get('mysql.host'), Config::get('mysql.user'), Config::get('mysql.password'), Config::get('mysql.db'));
		$db->dq("SET NAMES utf8");
	}
	catch(Exception $e)
	{
		die('Database connection error - check config file');
	}
}

# PostgreSQL Database Connection
elseif(Config::get('db') == 'postgres')
{
	require_once(YTTPATH. 'class.db.postgres.php');
	$db = DBConnection::init(new Database_Postgres);
	$db->connect(Config::get('postgres.host'), Config::get('postgres.user'), Config::get('postgres.password'), Config::get('postgres.db'));
	$db->dq("SET NAMES 'utf8'");
}

# SQLite3 (pdo_sqlite)
elseif(Config::get('db') == 'sqlite')
{
	try
	{
		require_once(YTTPATH. 'class.db.sqlite3.php');
		$db = DBConnection::init(new Database_Sqlite3);
		$db->connect(YTTPATH. 'db/todolist.db');
	}
	catch(Exception $e)
	{
		die('Database connection error - check config file');
	}
}
else {
	# It seems not installed
	die("Not installed. Run <a href=setup.php>setup.php</a> first.");
}
$db->prefix = Config::get('prefix');

require_once(YTTPATH. 'lang/class.default.php');
require_once(YTTPATH. 'lang/'.Config::get('lang').'.php');

$_yttinfo = array();

$needAuth = (Config::get('password') != '' || Config::get('multiuser') == 1) ? 1 : 0;
$multiUser = (Config::get('multiuser') == 1) ? 1 : 0;
if($needAuth && !isset($dontStartSession))
{
	if(Config::get('session') == 'files')
	{
		session_save_path(YTTPATH. 'tmp/sessions');
		ini_set('session.gc_maxlifetime', '1209600'); # 14 days session file minimum lifetime
		ini_set('session.gc_probability', 1);
		ini_set('session.gc_divisor', 10);
	}

	ini_set('session.use_cookies', true);
	ini_set('session.use_only_cookies', true);
	session_set_cookie_params(1209600, url_dir(Config::get('url')=='' ? $_SERVER['REQUEST_URI'] : Config::get('url'))); # 14 days session cookie lifetime
	session_name('ytt-session');
	session_start();
}

function is_logged()
{
	if(!isset($_SESSION['logged']) || !$_SESSION['logged']) return false;
	return true;
}

function is_readonly()
{
	$needAuth = (Config::get('password') != '' || Config::get('multiuser') == 1) ? 1 : 0;
	if(($needAuth && !is_logged()) || (Config::get('multiuser') == 1 && is_logged() && $_SESSION['role'] == 3)) return true;
	return false;
}

function is_admin()
{
	$needAuth = (Config::get('password') != '' || Config::get('multiuser') == 1) ? 1 : 0;
	if(!$needAuth) {
		return true;
	}

	if($needAuth && Config::get('multiuser') != 1 && is_logged()) {
		return true;
	}

	if($needAuth && Config::get('multiuser') == 1 && $_SESSION['role'] == 1) {
		return true;
	}

	return false;
}

function timestampToDatetime($timestamp)
{
	$format = Config::get('dateformat') .' '. (Config::get('clock') == 12 ? 'g:i A' : 'H:i');
	return formatTime($format, $timestamp);
}

function formatTime($format, $timestamp=0)
{
	$lang = Lang::instance();
	if($timestamp == 0) $timestamp = time();
	$newformat = strtr($format, array('F'=>'%1', 'M'=>'%2'));
	$adate = explode(',', date('n,'.$newformat, $timestamp), 2);
	$s = $adate[1];
	if($newformat != $format)
	{
		$am = (int)$adate[0];
		$ml = $lang->get('months_long');
		$ms = $lang->get('months_short');
		$F = $ml[$am-1];
		$M = $ms[$am-1];
		$s = strtr($s, array('%1'=>$F, '%2'=>$M));
	}
	return $s;
}

function _e($s)
{
	echo Lang::instance()->get($s);
}

function __($s)
{
	return Lang::instance()->get($s);
}

function yttinfo($v)
{
	global $_yttinfo;
	if(!isset($_yttinfo[$v])) {
		echo get_yttinfo($v);
	} else {
		echo $_yttinfo[$v];
	}
}

function get_yttinfo($v)
{
	global $_yttinfo;
	if(isset($_yttinfo[$v])) return $_yttinfo[$v];
	switch($v)
	{
		case 'template_url':
			$_yttinfo['template_url'] = get_yttinfo('ytt_url'). 'themes/'. Config::get('template') . '/';
			return $_yttinfo['template_url'];
		case 'url':
			$_yttinfo['url'] = Config::get('url');
			if($_yttinfo['url'] == '')
				$_yttinfo['url'] = 'http://'.$_SERVER['HTTP_HOST'] .($_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '').
									url_dir(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
			return $_yttinfo['url'];
		case 'ytt_url':
			$_yttinfo['ytt_url'] = Config::get('ytt_url');
			if($_yttinfo['ytt_url'] == '') $_yttinfo['ytt_url'] = url_dir(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
			return $_yttinfo['ytt_url'];
		case 'title':
			$_yttinfo['title'] = (Config::get('title') != '') ? htmlarray(Config::get('title')) : __('Your Tiny Todolist');
			return $_yttinfo['title'];
	}
}

function jsonExit($data)
{
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($data);
	exit;
}

?>