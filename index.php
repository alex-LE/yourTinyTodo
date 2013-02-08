<?php
/*
This file is part of yourTinyTodo by the yourTinyTodo community.
Copyrights for portions of this file are retained by their owners.

Based on myTinyTodo by Max Pozdeev
(C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('./init.php');

$lang = Lang::instance();

if($lang->rtl()) Config::set('rtl', 1);

if(!is_int(Config::get('firstdayofweek')) || Config::get('firstdayofweek')<0 || Config::get('firstdayofweek')>6) Config::set('firstdayofweek', 1);

define('TEMPLATEPATH', YTTPATH. 'themes/'.Config::get('template').'/');

require(TEMPLATEPATH. 'index.php');