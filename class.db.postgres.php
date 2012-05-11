<?php

/*
This file is part of yourTinyTodo by the yourTinyTodo community.
Copyrights for portions of this file are retained by their owners.

Based on myTinyTodo by Max Pozdeev
(C) Copyright 2009-2010 Max Pozdeev <maxpozdeev@gmail.com>

Licensed under the GNU GPL v3 license. See file COPYRIGHT for details.
*/

class DatabaseResult_Posgres
{

	var $parent;
	var $q;
	var $query;
	var $rows = NULL;
	var $affected = NULL;
	var $prefix = '';

	function __construct($query, &$h, $resultless = 0)
	{
		$this->parent = $h;
		$this->query = $query;

		$this->q = pg_query($this->parent->dbh, $query);

		if(!$this->q)
		{
			throw new Exception($this->parent->error());
		}
	}

	function affected()
	{
		if(is_null($this->affected))
		{
			$this->affected = pg_affected_rows($this->q);
		}
		return $this->affected;
	}

	function fetch_row()
	{
		return pg_fetch_row($this->q);
	}

	function fetch_assoc()
	{
		return pg_fetch_assoc($this->q);
	}

	function rows()
	{
		if (!is_null($this -> rows)) return $this->rows;
		$this->rows = pg_num_rows($this->q);
		return $this->rows;
	}
}

class Database_Postgres
{
	var $dbh;
	var $error_str;
	var $last_result;

	function __construct()
	{
	}

	function connect($host, $user, $pass, $db)
	{
		if(!$this->dbh = pg_connect('host='.$host.' dbname='.$db.' user='.$user.' password='.$pass))
		{
			throw new Exception(pg_last_error());
		}
		return true;
	}

	function last_insert_id($tablename)
	{
		$sql = "SELECT last_value FROM ".$tablename."_id_seq";
		$result = pg_query($this->dbh, $sql);
		return pg_fetch_result($result, 0, 'last_value');
	}	
	
	function error()
	{
		return pg_last_error($this->dbh);
	}

	function sq($query, $p = NULL)
	{
		$q = $this->_dq($query, $p);

		if($q->rows()) $res = $q->fetch_row();
		else return NULL;

		if(sizeof($res) > 1) return $res;
		else return $res[0];
	}

	function sqa($query, $p = NULL)
	{
		$q = $this->_dq($query, $p);

		if($q->rows()) $res = $q->fetch_assoc();
		else return NULL;

		if(sizeof($res) > 1) return $res;
		else return $res[0];
	}

	function dq($query, $p = NULL)
	{
		return $this->_dq($query, $p);
	}

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
		$this->last_result = new DatabaseResult_Posgres($query, $this, $resultless);
		return $this->last_result;
	}

	function affected()
	{
		return $this->last_result->affected();
	}

	function quote($s)
	{
		return '\''. pg_escape_string($s). '\'';
	}

	function quoteForLike($format, $s)
	{
		$s = str_replace(array('%','_'), array('\%','\_'), pg_escape_string($s));
		return '\''. sprintf($format, $s). '\'';
	}

	function table_exists($table)
	{
		$table = addslashes($table);
		$q = pg_num_rows(pg_query($this->dbh, "select * from pg_tables where schemaname='public' AND tablename = '$table';"));
		var_dump(pg_query($this->dbh, "select * from pg_tables where schemaname='public' AND tablename = '$table';"));
		var_dump($q);
		if($q > 0) return true;
		else return false;
	}
}

?>