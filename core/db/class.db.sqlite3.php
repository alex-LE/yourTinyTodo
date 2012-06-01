<?php
/*
This file is part of yourTinyTodo by the yourTinyTodo community.
Copyrights for portions of this file are retained by their owners.

Based on myTinyTodo by Max Pozdeev
(C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/

class DatabaseResult_Sqlite3 implements IDatabaseResult
{
	private $parent;
	private $q;
	var $query;
	var $prefix;
	var $rows = NULL;
	var $affected = NULL;

	/**
	 * @param $query
	 * @param $h Database
	 * @param int $resultless
	 * @throws Exception
	 */
	function __construct($query, &$h, $resultless = 0)
	{
		$this->parent = $h;
		$this->parent->lastQuery = $this->query = $query;

		if($resultless)
		{
			$r = $this->parent->dbh->exec($query);
			if($r === false) {
				$ei = $this->parent->dbh->errorInfo();
				throw new Exception($ei[2]);
			}
			$this->parent->affected = $r;
		}
		else
		{
			$this->q = $this->parent->dbh->query($query);
			if(!$this->q) {
				$ei = $this->parent->dbh->errorInfo();
				throw new Exception($ei[2]);
			}
			$this->parent->affected = $this->q->rowCount();
		}
	}

	function affected()
	{
		return $this->parent->affected;
	}

	function fetch_row()
	{
		return $this->q->fetch(PDO::FETCH_NUM);
	}

	function fetch_assoc()
	{
		return $this->q->fetch(PDO::FETCH_ASSOC);
	}

}

class Database_Sqlite3 extends Database
{
	/**
	 * @var $dbh PDO
	 */
	public $dbh;

	function __construct()
	{
	}

	/**
	 * @param $host = filename of sqlite file
	 * @param null $user not used
	 * @param null $pass not used
	 * @param null $db not used
	 * @return bool
	 * @throws Exception
	 */
	function connect($host, $user = null, $pass = null, $db = null)
	{
		try {
			$this->dbh = new PDO("sqlite:$host");
			$this->dbh->sqliteCreateFunction('concat', 'sqlite_udf_concat', 2);
			$this->dbh->sqliteCreateFunction('md5', 'sqlite_udf_md5', 1);
			$this->dbh->sqliteCreateFunction('now', 'sqlite_udf_now', 0);
		}
		catch(PDOException $e) {
			throw new Exception($e->getMessage());
		}
		return true;
	}

	function sq($query, $p = NULL)
	{
		$q = $this->_dq($query, $p);

		$res = $q->fetch_row();
		if($res === false) return NULL;

		if(sizeof($res) > 1) return $res;
		else return $res[0];
	}

	function sqa($query, $p = NULL)
	{
		$q = $this->_dq($query, $p);

		$res = $q->fetch_assoc();
		if($res === false) return NULL;

		if(sizeof($res) > 1) return $res;
		else return $res[0];
	}
	
	function dq($query, $p = NULL)
	{
		return $this->_dq($query, $p);
	}

	/* 
		for resultless queries like INSERT,UPDATE
	*/
	function ex($query, $p = NULL)
	{
		return $this->_dq($query, $p, 1);
	}

	private function _dq($query, $p = NULL, $resultless = 0)
	{
		if(!isset($p)) $p = array();
		elseif(!is_array($p)) $p = array($p);

		$m = explode('?', $query);

		if(sizeof($p)>0)
		{
			if(sizeof($m)< sizeof($p)+1) {
				throw new Exception("params to set MORE than query params");
			}
			if(sizeof($m)> sizeof($p)+1) {
				throw new Exception("params to set LESS than query params");
			}
			$query = "";
			for($i=0; $i<sizeof($m)-1; $i++) {
				$query .= $m[$i]. (is_null($p[$i]) ? 'NULL' : $this->quote($p[$i]));
			}
			$query .= $m[$i];
		}
		return new DatabaseResult_Sqlite3($query, $this, $resultless);
	}

	function affected()
	{
		return $this->affected;
	}

	function quote($s)
	{
		return $this->dbh->quote($s);
	}

	function quoteForLike($format, $s)
	{
		$s = str_replace(array('\\','%','_'), array('\\\\','\%','\_'), $s);
		return $this->dbh->quote(sprintf($format, $s)). " ESCAPE '\'";
	}

	function last_insert_id($tablename = null)
	{
		return $this->dbh->lastInsertId();
	}

	function table_exists($table)
	{
		$table = $this->dbh->quote($table);
		$q = $this->dbh->query("SELECT 1 FROM $table WHERE 1=0");
		if($q === false) return false;
		else return true;
	}

	function error()
	{
		return $this->dbh->errorInfo();
	}
}
function sqlite_udf_concat($str1, $str2)
{
	return $str1 . $str2;
}

function sqlite_udf_md5($a)
{
	return md5($a);
}

function sqlite_udf_now() {
	return date("Y-m-d H:i:s");
}