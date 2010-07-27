<?php

namespace TOGoS\DBC\MySQL;

use Exception;
use PHPUnit\Framework\TestCase;
use TOGoS\DBC;
use TOGoS\DBC\SQLIdentifier;
use TOGoS\DBC\SQLException;
use TOGoS\DBC\TestConfig;

/**
 * Test that we can connect to the master DB server and run some queries.
 */
class MySQLExecutorTest extends TestCase
{
	public function setUp() {
		if( TestConfig::$mysqlTestConfig === null ) {
			$this->markTestSkipped("TOGoS\\DBC\\TestConfig::\$mysqlTestConfig undefined.");
			return;
		}
		$this->executor = DBC::createExecutorFromConfig(TestConfig::$mysqlTestConfig);
	}
	
	public function testCreateTable() {
		$table = new SQLIdentifier("TestTable".rand(0,9999));
		$res = $this->executor->execute( "CREATE TABLE {table} ( ID INT PRIMARY KEY, Bar VARCHAR(10) )", array('table'=>$table) );
		try {
			$res = $this->executor->execute( "INSERT INTO {table} VALUES ( {id}, {bar} )", array('table'=>$table,'id'=>1,'bar'=>'Barry') );
			$this->assertEquals( 1, $res->getAffectedRowCount() );

			$res = $this->executor->execute( "INSERT INTO {table} VALUES ( {id}, {bar} )", array('table'=>$table,'id'=>2,'bar'=>'Larry') );
			$this->assertEquals( 1, $res->getAffectedRowCount() );

			try {
				$res = $this->executor->execute( "INSERT INTO {table} VALUES ( {id}, {bar} )", array('table'=>$table,'id'=>2,'bar'=>'Harry') );
				$this->fail( "Inserting duplicate primary key should have caused exception" );
			} catch( SQLException $e ) {
			}

			$res = $this->executor->execute( "SELECT * FROM {table}", array('table'=>$table) );
			$this->assertEquals( 2, count($res->getRows()) );

			$daCount = 0;
			$res = $this->executor->execute( "SELECT * FROM {table}", array('table'=>$table) );
			foreach( $res as $row ) {
				$this->assertEquals( 2, count($row) );
				++$daCount;
			}
			$this->assertEquals( 2, $daCount );
		} catch( Exception $e ) {
			// THANKS PHP!  >:{
			// http://bugs.php.net/bug.php?id=32100
			$this->executor->execute( "DROP TABLE {table}", array('table'=>$table) );
			throw $e;
		}
		$this->executor->execute( "DROP TABLE {table}", array('table'=>$table) );
	}

	protected function _testValueEncDec( $value ) {
		$res = $this->executor->execute( "SELECT {value} AS Value", array('value'=>$value) );
		$rows = $res->getRows();
		$this->assertEquals(1, count($rows));
		foreach( $rows as $row ) {
			$this->assertEquals($value, $row['Value']);
		} 	
	}
	
	public function testStringEscapes() {
		$this->_testValueEncDec("foo'bar");
		$this->_testValueEncDec('foo"bar');
		$this->_testValueEncDec("foo\nbar");
		$this->_testValueEncDec("foo\\bar");
		$this->_testValueEncDec("foo\\'bar");
		$this->_testValueEncDec("foo\\'bar");
		$this->_testValueEncDec("\x00, \n, \r, \, ', \" and \x1a");
		$this->_testValueEncDec("foo\\'; drop all them tables'; drop all them tables --");
	}
}
