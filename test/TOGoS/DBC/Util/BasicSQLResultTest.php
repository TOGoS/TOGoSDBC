<?php

class TOGoS_DBC_Util_BasicSQLResultTest extends PHPUnit_Framework_TestCase
{
	public function testGetAffectedRowCount() {
		$sqlResult = new TOGoS_DBC_Util_BasicSQLResult( 0, array(
			array('x'=>'1','y'=>'2'),
			array('x'=>'3','y'=>'4'),
		));
		
		$this->assertEquals( 0, $sqlResult->getAffectedRowCount() );
		$this->assertEquals( 2, count($sqlResult->getRows()) );
		
		$rc = 0;
		foreach( $sqlResult as $row ) {
			$this->assertEquals( 2, count($row) );
			++$rc;
		}
		$this->assertEquals( 2, $rc );
	}
}
