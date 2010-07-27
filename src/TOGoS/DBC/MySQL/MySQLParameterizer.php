<?php

namespace TOGoS\DBC\MySQL;

use TOGoS\DBC\Util\Parameterizer;

class MySQLParameterizer extends Parameterizer
{
	public static $instance;
	public static function getInstance() {
		if( self::$instance === null ) self::$instance = new self();
		return self::$instance;
	}
	
	protected function sqlEncodeString( $value ) {
		return "'".mysql_real_escape_string($value)."'";
	}
}
