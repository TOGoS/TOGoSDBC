<?php

namespace TOGoS\DBC\Util;

use TOGoS\DBC\SQLResult;

/**
 * A really simple SQLResult class that you can use if you don't
 * want to do anything fancy like lazy initialization.
 */
class BasicSQLResult implements SQLResult
{
	protected $affectedRowCount;
	protected $rows;

	public function __construct( $affectedRowCount, $rows ) {
		$this->affectedRowCount = $affectedRowCount;
		$this->rows = $rows;
	}
	
	public function getIterator() {
		return new \ArrayIterator( $this->getRows() );
	}
	
	public function getRows() {
		return $this->rows;
	}
	
	public function getAffectedRowCount() {
		return $this->affectedRowCount;
	}
}
