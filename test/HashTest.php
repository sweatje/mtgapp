<?php

require_once 'model/Hash.php';

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class TestHashClass extends PHPUnit_Framework_TestCase {
	protected $hash;
        function setup() {
		$this->hash = new Hash;
	}
	function testGetReturnsNullForUnsetKey() {
                $this->assertEquals(null, $this->hash->get('unset key'));
        }
	function testSetStoresValueWithKeyForGetRetrieval() {
		$this->hash->set('key','value');
		$this->assertEquals('value', $this->hash->get('key'));
	}
	function testHashTreatsAttributeSetSameAsGetSetMethods() {
		$this->hash->key = 'value';
		$this->assertEquals('value', $this->hash->get('key'));
		$this->hash->set('key2','value2');
		$this->assertEquals('value2', $this->hash->key2);
	}
	function testHashAllowsAccessAsAnArray() {
		$this->hash['key'] = 'value';
		$this->assertEquals('value', $this->hash['key']);
	}
	function testHashUsesContructorToPopulate() {
		$hash = new Hash(array('foo' => 'bar'));
		$this->assertEquals('bar',$hash->foo);
	}
	function testHashUsesConstructureToPopulateFromObject() {
		$obj = new StdClass;
		$obj->test1 = 'val1';
		$obj->test2 = 'val2';
		$hash = new Hash($obj);
		$this->assertEquals('val2',$hash['test2']);
	}
	function testHashCountMethodReturnsNumberOfHashItems() {
		$this->hash->set('one',1);
		$this->hash->set('two',2);
		$this->assertEquals(2,$this->hash->count());
	}
}
