<?php

namespace TOGoS\DBC;

use PHPUnit\Framework\TestCase;
use TOGoS\DBC\SQLLiteral;

class DBExceptionTest extends TestCase
{
	function testConstructMessageOnly() {
		$e = new DBException("foo");
		$this->assertEquals("foo",$e->getMessage());
		$this->assertNull($e->getSql());
		$this->assertNull($e->getArgs());
	}

	function testConstructQuery() {
		$e = new DBException("foo","select *",array('a'=>'x'));
		$this->assertEquals("foo",$e->getMessage());
		$this->assertEquals("select *",$e->getSql());
		$this->assertEquals(array('a'=>'x'),$e->getArgs());
	}
}
