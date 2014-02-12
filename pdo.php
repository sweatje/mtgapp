<?php
require_once 'model/Hash.php';

function db_factory($filename='mtgapp.sqlite3') {
	$prodfile = 'mtgapp.prod.sqlite3';
	$dsn = (file_exists($prodfile)) ? 'sqlite:'.$prodfile : 'sqlite:'.$filename;
	$db = new PDO($dsn);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $db;
}
function db() {
	static $db;
	if (!$db) $db = db_factory();
	return $db;
}

class Model {
	protected $db;
	function __construct($db=false) {
		$this->db = ($db) ? $db : db();
		$this->init();
	}
	function init() {
	}
	function exec($sql,$bind=array()) {
		$stmt = $this->db->prepare($sql);
		foreach($bind as $key => $val) {
			$stmt->bindValue(':'.$key, $val);
		}
		$stmt->execute();
		return $stmt;
	}
	function getRow($sql,$bind=array()) {
		$result = $this->exec($sql,$bind);
		$row = $result->fetch();
		$ret = new Hash($row);
		return $ret;
	}
	function getAll($sql,$bind=array()) {
		$ret = array();
		foreach( $this->exec($sql,$bind)->fetchAll() as $row ) $ret[] = new Hash($row);
		return $ret;
	}
	function getById($id) {
		$sql = 'select * from '.static::$table.' where id = :id';
		return $this->getRow($sql,array('id'=>$id));
	}
	function All() {
		return $this->getAll('select * from '.static::$table);
	}
	function getByPlan($plan) {
		return $this->getAll(
			'select * from '.static::$table.' where plan_id = :plan'
			,array('plan'=>$plan));
	}
	const UPSERT_CHK = 'select * from %s where %s = :%s';
	const UPSERT_UPD = 'update %s set %s where %s = :%s';
	const UPSERT_INS = 'insert into %s (%s) values (%s)';
	function upsert($table,$id,$fields) {
		$sql = sprintf(self::UPSERT_CHK,$table,$id,$id);
		$row = $this->getRow($sql,[$id=>$fields[$id]]);
		if ($row->count()) {
			$field_sql = '';
			foreach (array_keys($fields) as $field) { if ($field != $id) $field_sql .= $field.' = :'.$field.', '; }
			$sql = sprintf(self::UPSERT_UPD, $table, trim($field_sql,', '),$id,$id);
			$this->exec($sql,$fields);	
		} else {
			$sql = sprintf(self::UPSERT_INS,$table
				,implode(', ',array_keys($fields))
				,':'.implode(', :',array_keys($fields)));
			$this->exec($sql,$fields);	
		}
	}
}

