<?php

namespace TOGoS\DBC\Util;

use PHPUnit\Framework\TestCase;
use TOGoS\DBC\SQLLiteral;

class ParameterizerTest extends TestCase
{
	function setUp() {
		$this->PZR = Parameterizer::getInstance();
	}

	function testNoParameters() {
		$this->assertEquals( "xxx", $this->PZR->parameterize("xxx",array()) );
		$this->assertNotEquals( "xxx", $this->PZR->parameterize("yyy",array()) );
	}
	
	function testStringParameter() {
		$this->assertEquals( "select 'xxx'", $this->PZR->parameterize("select {value}",array('value'=>'xxx')) );
	}
	function testIntParameter() {
		$this->assertEquals( "select 123", $this->PZR->parameterize("select {value}",array('value'=>123)) );
	}
	function testArrayParameter() {
		$this->assertEquals( "select ('xxx','yyy')", $this->PZR->parameterize("select {value}",array('value'=>array('xxx','yyy'))) );
	}
	function testSomeParameters() {
		$e = "select Mass from Thing where ID = 45 or Name in ('Coffee Cup','Radish')";
		$i = "select {column} from {table} where ID = {id} or Name in {names}";
		$a = array('column'=>new SQLLiteral('Mass'),
				   'table'=>new SQLLiteral('Thing'),
				   'id'=>123,
				   'names'=>array('Coffee Cup','Radish'));
		$this->assertEquals( $e, $this->PZR->parameterize($i,$a) );
	}
}
