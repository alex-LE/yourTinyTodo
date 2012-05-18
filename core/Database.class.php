<?
require_once(YTTPATH.'core/interfaces/databaseresult.interface.php');

abstract class Database {
	public $prefix;
	public $dbh;
	public $error_str;
	public $last_result;
	public $affected = null;
	public $lastQuery;

	/**
	 * @abstract
	 * @param $host
	 * @param null $user
	 * @param null $pass
	 * @param null $db
	 * @return IDatabase
	 */
	abstract public function connect($host, $user = null, $pass = null, $db = null);

	/**
	 * @abstract
	 * @param null $tablename
	 * @return mixed
	 */
	abstract public function last_insert_id($tablename = null);
	abstract public function error();
	abstract public function sq($query, $p = NULL);
	abstract public function sqa($query, $p = NULL);
	/**
	 * @abstract
	 * @param $query
	 * @param null $p
	 * @return IDatabaseResult
	 */
	abstract public function dq($query, $p = NULL);
	abstract public function ex($query, $p = NULL);
	abstract public function affected();
	abstract public function quote($s);
	abstract public function quoteForLike($format, $s);
	abstract public function table_exists($table);
}