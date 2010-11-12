<?php

interface TOGoS_DBC_SQLExecutor
{
	/**
	 * @param string $sql String of SQL, possibly with {parameters}
	 * @param array $bindings associative array of parameter values
	 * @return SQLResult
	 * @throws DBException
	 */
	public function execute( $sql, array $args=array() );
}
