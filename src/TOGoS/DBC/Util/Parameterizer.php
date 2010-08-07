<?php

namespace TOGoS\DBC\Util;

use TOGoS\DBC\SQLException;
use TOGoS\DBC\SQLLiteral;

class Parameterizer
{
	public static $instance;
	public static function getInstance() {
		if( self::$instance === null ) self::$instance = new self();
		return self::$instance;
	}
	
	protected function sqlEncodeString( $value ) {
		return "'".str_replace("'","''",$value)."'";
	}
	
	public function sqlEncode( $value ) {
		if( is_string($value) ) {
			return $this->sqlEncodeString( $value );
		} else if( is_int($value) ) {
			return (string)$value;
		} else if( is_array($value) ) {
			$r = array();
			foreach( $value as $x ) $r[] = $this->sqlEncode($x);
			return '('.implode(',',$r).')';
		} else if( $value instanceof SQLLiteral ) {
			return (string)$value;
		} else if( $value === null ) {
			return 'NULL';
		} else {
			throw new SQLException( "Don't know how to SQL-encode value of type/class ".gettype($value).'/'.@get_class($value) );
		}
	}
	
	public function parameterize( $sql, $args ) {
		$usedVars = array();
		$parameterizer = $this;
		return preg_replace_callback( '/{([^}]+)}/', function($bif) use ($sql,$args,$parameterizer) {
			if( !array_key_exists($bif[1],$args) ) {
				throw new SQLException( "Unspecified parameter {$bif[0]}", $sql, $args );
			}
			return $parameterizer->sqlEncode($args[$bif[1]]);
		}, $sql);
	}
}
