<?php

namespace TOGoS\DBC\MySQL;

use TOGoS\DBC\SQLResult;

class MySQLResult implements SQLResult
{
	protected $rows;
	protected $affectedRowCount;

	public function __construct( $mysqlResult ) {
		
		if( is_resource($mysqlResult) ) {
			
		}
	}
	
	function getIterator() {
		return new \ArrayIterator( $this->getRows() );
	}
	
	function getRows() {
	}
	
	public function getAffectedRowCount() {
		return $this->affectedRowCount;
	}
}
