<?php

abstract class TOGoS_DBC_SQLExecutorTest extends PHPUnit_Framework_TestCase
{
	// setUp() should set $this->executor
	
	public function testCreateTable() {
		$table = new TOGoS_DBC_SQLIdentifier("TestTable".rand(0,9999));
		$res = $this->executor->execute( "CREATE TABLE {table} ( ID INT PRIMARY KEY, Bar VARCHAR(10) )", array('table'=>$table) );
		try {
			$res = $this->executor->execute( "INSERT INTO {table} VALUES ( {id}, {bar} )", array('table'=>$table,'id'=>1,'bar'=>'Barry') );
			$this->assertEquals( 1, $res->getAffectedRowCount() );

			$res = $this->executor->execute( "INSERT INTO {table} VALUES ( {id}, {bar} )", array('table'=>$table,'id'=>2,'bar'=>'Larry') );
			$this->assertEquals( 1, $res->getAffectedRowCount() );

			try {
				$res = $this->executor->execute( "INSERT INTO {table} VALUES ( {id}, {bar} )", array('table'=>$table,'id'=>2,'bar'=>'Harry') );
				$this->fail( "Inserting duplicate primary key should have caused exception" );
			} catch( TOGoS_DBC_SQLException $e ) {
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

	public function testAutoIncrement() {
		$table = new TOGoS_DBC_SQLIdentifier("TestTable".rand(0,9999));
		$this->executor->execute( "CREATE TABLE {table} ( ID INT AUTO_INCREMENT PRIMARY KEY, Bar VARCHAR(10) )", array('table'=>$table) );
		$res = $this->executor->execute( "INSERT INTO {table} (Bar) VALUES ({bar})", array('table'=>$table, 'bar'=>'hello') );
		$id1 = $res->getLastInsertId();
		$res = $this->executor->execute( "INSERT INTO {table} (Bar) VALUES ({bar})", array('table'=>$table, 'bar'=>'goodbye') );
		$id2 = $res->getLastInsertId();
		$this->assertEquals( $id1+1, $id2 );
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
