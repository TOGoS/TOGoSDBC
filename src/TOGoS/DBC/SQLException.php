<?php

namespace TOGoS\DBC;

/**
 * Thrown when there is an error parameterizing SQL or executing an SQL statement
 */
class SQLException extends DBException
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
