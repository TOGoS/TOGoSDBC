<?php

class TOGoS_DBC
{
	public static function createExecutorFromConfig( $conf ) {
		if( !is_array($conf) ) {
			throw new LogicException("Config must be an array for now; ".
									 "given".gettype($conf) );
		}
		$driver = $conf['driver'];
		if( $driver == 'mysql' ) {
			return TOGoS_DBC_MySQL_MySQLExecutor::createFromConfig( $conf );
		} else {
			throw new Exception( "Unsupported DB driver: $driver" );
		}
	}
}
