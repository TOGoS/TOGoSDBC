<?php

class TOGoS_DBC_MySQLi_MySQLiParameterizer extends TOGoS_DBC_Util_Parameterizer
{
	protected $link;
	
	public function __construct( $link ) {
		$this->link = $link;
	}
	
	protected function sqlEncodeString( $value ) {
		return "'".$this->link->real_escape_string($value)."'";
	}
}
