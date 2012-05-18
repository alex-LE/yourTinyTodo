<?

interface IDatabase {
	/**
	 * @abstract
	 * @param $host
	 * @param null $user
	 * @param null $pass
	 * @param null $db
	 * @return IDatabase
	 */
	public function connect($host, $user = null, $pass = null, $db = null);

	/**
	 * @abstract
	 * @param null $tablename
	 * @return mixed
	 */
	public function last_insert_id($tablename = null);
	public function error();
	public function sq($query, $p = NULL);
	public function sqa($query, $p = NULL);
	/**
	 * @abstract
	 * @param $query
	 * @param null $p
	 * @return IDatabaseResult
	 */
	public function dq($query, $p = NULL);
	public function ex($query, $p = NULL);
	public function affected();
	public function quote($s);
	public function quoteForLike($format, $s);
	public function table_exists($table);
}