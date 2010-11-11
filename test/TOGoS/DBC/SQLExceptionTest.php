<?php

class TOGoS_DBC_SQLExceptionTest extends PHPUnit_Framework_TestCase
{
	function testConstructMessageOnly() {
		$e = new TOGoS_DBC_SQLException("foo");
		$this->assertEquals("foo",$e->getMessage());
		$this->assertNull($e->getSql());
		$this->assertNull($e->getArgs());
	}

	function testConstructQuery() {
		$e = new TOGoS_DBC_SQLException("foo","select *",array('a'=>'x'));
		$this->assertEquals("foo",$e->getMessage());
		$this->assertEquals("select *",$e->getSql());
		$this->assertEquals(array('a'=>'x'),$e->getArgs());
	}
}
