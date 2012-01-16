<?php

class TOGoS_DBC_MySQL_MySQLParameterizer extends TOGoS_DBC_Util_Parameterizer
{
	protected $link;
	
	public function __construct($link) {
		$this->link = $link;
	}
	
	protected function sqlEncodeString( $value ) {
		return "'".mysql_real_escape_string($value, $this->link)."'";
	}
}
