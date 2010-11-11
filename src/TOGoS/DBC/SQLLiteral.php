<?php

class TOGoS_DBC_SQLLiteral
{
	protected $sql;
	public function __construct($sql) {
		$this->sql = $sql;
	}
	public function __toString() {
		return $this->sql;
	}
}
