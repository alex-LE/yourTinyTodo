<?php
/*
This file is part of yourTinyTodo by the yourTinyTodo community.
Copyrights for portions of this file are retained by their owners.

Based on myTinyTodo by Max Pozdeev
(C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/

# Configuration goes here
$config = array();

# Database type: sqlite or mysql
$config['db'] = '';

# Specify these settings if you selected above to use Mysql
$config['mysql.host'] = "localhost";
$config['mysql.db'] = "yytinytodo";
$config['mysql.user'] = "user";
$config['mysql.password'] = "";

# Tables prefix
$config['prefix'] = "ytt_";

# These two parameters are used when yourtinytodo index.php called not from installation directory
# 'url' - URL where index.php is called from (ex.: http://site.com/todo.php)
# 'ytt_url' - directory URL where yourtinytodo is installed (with trailing slash) (ex.: http://site.com/lib/yourtinytodo/)
$config['url'] = '';
$config['ytt_url'] = '';

# Language pack
$config['lang'] = "en";

# Specify password here to protect your tasks from modification,
#  or leave empty that everyone could read/write todolist
$config['password'] = "";

# To disable smart syntax uncomment the line below
#$config['smartsyntax'] = 0;

# Markdown support
$config['markdown'] = 0;

# multi user support
$config['multiuser'] = 0;

# Default Time zone
$config['timezone'] = 'UTC';

# To disable auto adding selected tag  comment out the line below or set value to 0
$config['autotag'] = 1;

# duedate calendar format: 1 => y-m-d (default), 2 => m/d/y, 3 => d.m.y
$config['duedateformat'] = 1;

# First day of week: 0-Sunday, 1-Monday, 2-Tuesday, .. 6-Saturday
$config['firstdayofweek'] = 1;

# select session handling mechanism: files or default (php default)
$config['session'] = 'default';

# Date/time formats
$config['clock'] = 24;
$config['dateformat'] = 'j M Y';
$config['dateformatshort'] = 'j M';

# Show task date in list
$config['showdate'] = 0;

$config['auth_bypass'] = 'none';
$config['debugmode'] = 0;
?>