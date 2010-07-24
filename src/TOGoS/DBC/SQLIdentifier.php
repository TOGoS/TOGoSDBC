<?php

namespace TOGoS\DBC;

class SQLIdentifier extends SQLLiteral
{
	protected $name;
	public function __construct($name) {
		$this->name = $name;
	}
	public function __toString() {
		return "`".str_replace("`","``",$this->name)."`";
	}
}
