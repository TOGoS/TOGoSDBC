<?php

class TOGoS_DBC_MySQL_MySQLiExecutorTest extends TOGoS_DBC_SQLExecutorTest
{
	public function setUp() {
		if( TOGoS_DBC_TestConfig::$mysqliTestConfig === null ) {
			$this->markTestSkipped("TOGoS\\DBC\\TestConfig::\$mysqliTestConfig undefined.");
			return;
		}
		$this->executor = TOGoS_DBC_MySQLi_MySQLiExecutor::createFromConfig(TOGoS_DBC_TestConfig::$mysqliTestConfig);
	}	
}
