<?php

namespace TOGoS\DBC;

class DBException extends \Exception
{
	protected $sql;
	protected $args;

	public function __construct( $message, $sql=null, $args=null ) {
		parent::__construct($message);
		$this->sql = $sql;
		$this->args = $args;
	}

	public function getSql() { return $this->sql; }
	public function getArgs() { return $this->args; }
}
