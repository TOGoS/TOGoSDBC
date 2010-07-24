<?php

namespace TOGoS;

use Exception;
use TOGoS\DBC\DBConnectionException;
use TOGoS\DBC\MySQL\MySQLExecutor;

class DBC
{
	public static function createExecutorFromConfig( $conf ) {
		if( !is_array($conf) ) {
			throw new LogicException("Config must be an array for now; ".
									 "given".gettype($conf) );
		}
		$driver = $conf['driver'];
		if( $driver == 'mysql' ) {
			return MySQLExecutor::createFromConfig( $conf );
		} else {
			throw new Exception( "Unsupported DB driver: $driver" );
		}
	}
}