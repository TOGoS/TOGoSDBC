<?php

class TOGoS_DBC_SQLIdentifier extends TOGoS_DBC_SQLLiteral
{
	protected $name;
	public function __construct($name) {
		$this->name = $name;
	}
	public function __toString() {
		return "`".str_replace("`","``",$this->name)."`";
	}
}
