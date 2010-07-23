<?php

namespace TOGoS\DBC\Util;

use PHPUnit\Framework\TestCase;

class BasicSQLResultTest extends TestCase
{
	public function testGetAffectedRowCount() {
		$sqlResult = new BasicSQLResult( 0, array(
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
