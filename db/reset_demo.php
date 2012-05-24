<?
error_reporting(E_ALL);
ini_set('display_errors',1);
/**
 * reset database
 */
$config = array();
$config['mysql.host'] = 'alexphpc-db.my.phpcloud.com';
$config['mysql.db'] = 'alexphpc';
$config['mysql.user'] = 'alexphpc';
$config['mysql.password'] = 'Alexander82';

$con = mysql_connect($config['mysql.host'], $config['mysql.user'], $config['mysql.password']);
mysql_select_db($config['mysql.db'],$con);

$import = file_get_contents('db/ytt_demo.sql');

$import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
$import = preg_replace ("%^--(.*)\n%mU", '', $import);
$import = preg_replace ("%^$\n%mU", '', $import);

mysql_real_escape_string($import);
$import = explode (";", $import);

foreach ($import as $imp){
	if ($imp != '' && $imp != ' '){
		mysql_query($imp, $con);
	}
}

mysql_close($con);

/**
 * reset config file
 */
copy('db/config_org.php', 'db/config.php');

echo 'done';