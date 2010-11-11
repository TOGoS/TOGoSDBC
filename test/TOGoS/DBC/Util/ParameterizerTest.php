<?php

class TOGoS_DBC_Util_ParameterizerTest extends PHPUnit_Framework_TestCase
{
	function setUp() {
		$this->PZR = TOGoS_DBC_Util_Parameterizer::getInstance();
	}

	function testNoParameters() {
		$this->assertEquals( "xxx", $this->PZR->parameterize("xxx",array()) );
		$this->assertNotEquals( "xxx", $this->PZR->parameterize("yyy",array()) );
	}
	
	function testStringParameter() {
		$this->assertEquals( "select 'xxx'", $this->PZR->parameterize("select {value}",array('value'=>'xxx')) );
	}
	function testStringWithQuoteParameter() {
		$this->assertEquals( "select 'xx''yy'", $this->PZR->parameterize("select {value}",array('value'=>"xx'yy")) );
	}
	function testIntParameter() {
		$this->assertEquals( "select 123", $this->PZR->parameterize("select {value}",array('value'=>123)) );
	}
	function testBooleanParameter() {
		$this->assertEquals( "select TRUE, FALSE", $this->PZR->parameterize("select {t}, {f}",array('t'=>true, 'f'=>false)) );
	}
	function testNullParameter() {
		$this->assertEquals( "select NULL", $this->PZR->parameterize("select {value}",array('value'=>null)) );
	}
	function testArrayParameter() {
		$this->assertEquals( "select ('xxx','yyy')", $this->PZR->parameterize("select {value}",array('value'=>array('xxx','yyy'))) );
	}
	function testIdentifierParameter() {
		$this->assertEquals( "select `Foo``Bar` from `Baz`",
							 $this->PZR->parameterize("select {column} from {table}",
													  array('column'=>new TOGoS_DBC_SQLIdentifier('Foo`Bar'),
															'table'=>new TOGoS_DBC_SQLIdentifier('Baz'))));
	}
	function testSomeParameters() {
		$e = "select Mass from Thing where ID = 456 or Name in ('Coffee Cup','Radish')";
		$i = "select {column} from {table} where ID = {id} or Name in {names}";
		$a = array('column'=>new TOGoS_DBC_SQLLiteral('Mass'),
				   'table'=>new TOGoS_DBC_SQLLiteral('Thing'),
				   'id'=>456,
				   'names'=>array('Coffee Cup','Radish'));
		$this->assertEquals( $e, $this->PZR->parameterize($i,$a) );
	}
	function testMissingParameter() {
		$sql = "SELECT {foo}, {bar}, {baz}";
		$args = array('bar'=>1,'baz'=>2);
		try {
			$this->PZR->parameterize($sql,$args);
			$this->fail("Parameterizer should have thrown SQLException due to missing arguments");
		} catch( TOGoS_DBC_SQLException $e ) {
		}
	}
}
