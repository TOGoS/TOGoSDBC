<?php

class TOGoS_DBC_Util_Parameterizer_Replacer
{
	protected $sql, $args, $parameterizer;
	public function __construct($sql,$args,$parameterizer) {
		$this->sql = $sql;
		$this->args = $args;
		$this->parameterizer = $parameterizer;
	}
	
	public function __invoke( $bif ) {
		if( !array_key_exists($bif[1],$this->args) ) {
			throw new TOGoS_DBC_SQLException( "Unspecified parameter {{$bif[0]}}", $this->sql, $this->args );
		}
		return $this->parameterizer->sqlEncode($this->args[$bif[1]]);
	}
}

class TOGoS_DBC_Util_Parameterizer
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
		} else if( $value === true ) {
			return 'TRUE';
		} else if( $value === false ) {
			return 'FALSE';
		} else if( $value instanceof TOGoS_DBC_SQLLiteral ) {
			return (string)$value;
		} else if( $value === null ) {
			return 'NULL';
		} else {
			throw new TOGoS_DBC_SQLException( "Don't know how to SQL-encode value of type/class ".gettype($value).'/'.@get_class($value) );
		}
	}
	
	public function parameterize( $sql, $args ) {
		$parameterizer = $this;
		return preg_replace_callback( '/{([^}]+)}/', array(new TOGoS_DBC_Util_Parameterizer_Replacer($sql,$args,$parameterizer),'__invoke'), $sql );
	}
}
