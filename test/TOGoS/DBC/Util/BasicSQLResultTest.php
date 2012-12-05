<?php

class TOGoS_DBC_Util_BasicSQLResultTest extends PHPUnit_Framework_TestCase
{
	public function testGetAffectedRowCount() {
		$sqlResult = new TOGoS_DBC_Util_BasicSQLResult( array(
			array('x'=>'1','y'=>'2'),
			array('x'=>'3','y'=>'4'),
		), 0, 123);
		
		$this->assertEquals( 0, $sqlResult->getAffectedRowCount() );
		$this->assertEquals( 2, count($sqlResult->getRows()) );
		$this->assertEquals( 123, $sqlResult->getLastInsertId() );
		
		$rc = 0;
		foreach( $sqlResult as $row ) {
			$this->assertEquals( 2, count($row) );
			++$rc;
		}
		$this->assertEquals( 2, $rc );
	}
}
