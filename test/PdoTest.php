<?php

require_once 'pdo.php';

class MockPDO extends PDO {
	function __construct() {}
}

/**  
* @backupGlobals disabled  
* @backupStaticAttributes disabled  
*/ 
class TestModelClasses extends PHPUnit_Framework_TestCase {
	function testModelExecCallsDbPrepareAndExecute() {
		$statement = 'sql statement';
		$db = $this->getMock('MockPDO', array('prepare'));
		$sql = $this->getMock('PDOStatement', array('execute'));
		$sql->expects($this->once())
			->method('execute')
			->with();
		$db->expects($this->once())
			->method('prepare')
			->with($statement)
			->will($this->returnValue($sql));
		$model = new Model($db);
		$model->exec($statement);
	}
	function testModelExecWithSecondParameterBindParameters() {
		$statement = 'sql statement 2';
		$bind = array('k1'=>'v1','k2'=>'v2');
		$db = $this->getMock('MockPDO', array('prepare'));
		$sql = $this->getMock('PDOStatement', array('execute','bindValue'));
		$sql->expects($this->at(0))
			->method('bindValue')
			->with(':k1','v1');
		$sql->expects($this->at(1))
			->method('bindValue')
			->with(':k2','v2');
		$sql->expects($this->at(2))
			->method('execute')
			->with();
		$db->expects($this->once())
			->method('prepare')
			->with($statement)
			->will($this->returnValue($sql));
		$model = new Model($db);
		$model->exec($statement,$bind);
	}
	function testModelGetrowCallsFetch() {
		$statement = 'sql statement 3';
		$result = array('result');
		$db = $this->getMock('MockPDO', array('prepare'));
		$sql = $this->getMock('PDOStatement', array('execute','fetch'));
		$sql->expects($this->at(0))
			->method('execute')
			->with();
		$sql->expects($this->at(1))
			->method('fetch')
			->with()
			->will($this->returnValue($result));;
		$db->expects($this->once())
			->method('prepare')
			->with($statement)
			->will($this->returnValue($sql));
		$model = new Model($db);
		$this->assertEquals(new Hash($result), $model->getRow($statement));
	}
	function testModelGetallCallsFetchAll() {
		$statement = 'sql statement 4';
		$result = array(array('result1'),array('result2'));
		$endresult = array();
		foreach ($result as $row) $endresult[] = new Hash($row);
		$db = $this->getMock('MockPDO', array('prepare'));
		$sql = $this->getMock('PDOStatement', array('execute','fetchAll'));
		$sql->expects($this->at(0))
			->method('execute')
			->with();
		$sql->expects($this->at(1))
			->method('fetchAll')
			->with()
			->will($this->returnValue($result));;
		$db->expects($this->once())
			->method('prepare')
			->with($statement)
			->will($this->returnValue($sql));
		$model = new Model($db);
		$this->assertEquals($endresult, $model->getAll($statement));
	}
	function testUpsertMethodWillInsertIfNoDataFound() {
		$fields = ['testid'=>1,'val'=>'foo'];
		$stmt = $this->getMock('PDOStatement', array('fetch'));
		$stmt->expects($this->at(0))
			->method('fetch')
			->with()
			->will($this->returnValue([]));
		$model = $this->getMock('Model', array('exec'));
		$model->expects($this->at(0))
			->method('exec')
			->with('select * from testtab where testid = :testid',['testid'=>1])
			->will($this->returnValue($stmt));
		$model->expects($this->at(1))
			->method('exec')
			->with('insert into testtab (testid, val) values (:testid, :val)',$fields)
			->will($this->returnValue($stmt));
		$model->upsert('testtab','testid',$fields);
	}
	function testUpsertMethodWillUpdateIfDataFound() {
		$fields = ['testid'=>1,'val'=>'foo','val2'=>'bar'];
		$old = ['testid'=>1,'val'=>'bar','val2'=>'baz'];
		$stmt = $this->getMock('PDOStatement', array('fetch'));
		$stmt->expects($this->at(0))
			->method('fetch')
			->with()
			->will($this->returnValue($old));
		$model = $this->getMock('Model', array('exec'));
		$model->expects($this->at(0))
			->method('exec')
			->with('select * from testtab where testid = :testid',['testid'=>1])
			->will($this->returnValue($stmt));
		$model->expects($this->at(1))
			->method('exec')
			->with('update testtab set val = :val, val2 = :val2 where testid = :testid',$fields)
			->will($this->returnValue($stmt));
		$model->upsert('testtab','testid',$fields);
	}
	function testUpsertMethodWillDoNothingIfDataFoundAndSame() {
	}
}
