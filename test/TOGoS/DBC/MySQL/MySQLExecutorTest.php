<?php

class TOGoS_DBC_MySQL_MySQLExecutorTest extends TOGoS_DBC_SQLExecutorTest
{
	public function setUp() {
		if( TOGoS_DBC_TestConfig::$mysqlTestConfig === null ) {
			$this->markTestSkipped("TOGoS\\DBC\\TestConfig::\$mysqlTestConfig undefined.");
			return;
		}
		$this->executor = TOGoS_DBC_MySQL_MySQLExecutor::createFromConfig(TOGoS_DBC_TestConfig::$mysqliTestConfig);
	}
}
