<?php

namespace TOGoS\DBC\Util;

class Parameterizer
{
	public static $instance;
	public static function getInstance() {
		if( self::$instance === null ) self::$instance = new self();
		return self::$instance;
	}
	
	public function parameterize( $sql, $args ) {
		return $sql;
	}
}
