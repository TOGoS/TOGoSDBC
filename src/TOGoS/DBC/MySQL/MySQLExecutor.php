<?php

namespace TOGoS\DBC\MySQL;

use TOGoS\DBC\SQLExecutor;
use TOGoS\DBC\SQLError;
use TOGoS\DBC\Util\Parameterizer;

class MySQLExecutor implements SQLExecutor
{
	protected $mysqlLink;
	
	function __construct( $mysqlLink ) {
		$this->mysqlLink = $mysqlLink;
	}
	
	function execute( $inSql, $args ) {
		$sql = Parameterizer::getInstance()->parameterize( $inSql, $args );
		$mySqlResult = mysql_query( $sql, $this->mysqlLink );
		if( $mysqlResult === false ) {
			throw new SQLError( mysql_error($this->mysqlLink), $sql );
		} else {
			return new MySQLResult( $mysqlResult );
		}
	}
}
