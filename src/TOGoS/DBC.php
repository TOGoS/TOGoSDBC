<?php

class TOGoS_DBC
{
	public static function createExecutorFromConfig( $conf ) {
		if( !is_array($conf) ) {
			throw new LogicException("Config must be an array for now; ".
			                         "given ".var_export($conf,true) );
		}
		switch( $conf['driver'] ) {
		case( 'mysql' ):
			return TOGoS_DBC_MySQL_MySQLExecutor::createFromConfig( $conf );
		case( 'mysqli' ):
			return TOGoS_DBC_MySQLi_MySQLiExecutor::createFromConfig( $conf );
		default:
			throw new Exception( "Unsupported DB driver: {$conf['driver']}" );
		}
	}
}
