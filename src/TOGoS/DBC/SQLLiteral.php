<?php

namespace TOGoS\DBC;

class SQLLiteral
{
	protected $sql;
	public function __construct($sql) {
		$this->sql = $sql;
	}
	public function __toString() {
		return $this->sql;
	}
}
