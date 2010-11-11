<?php

class TOGoS_DBC_Util_MySQLParameterizer extends TOGoS_DBC_MySQL_Parameterizer
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
