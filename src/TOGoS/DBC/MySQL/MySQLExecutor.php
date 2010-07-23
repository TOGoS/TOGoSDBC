<?php

namespace TOGoS\DBC\MySQL;

use TOGoS\DBC\SQLExecutor;
use TOGoS\DBC\SQLError;
use TOGoS\DBC\Util\Parameterizer;
use TOGoS\DBC\Util\BasicSQLResult;

class MySQLExecutor implements SQLExecutor
{
	protected $mysqlLink;
	
	function __construct( $mysqlLink ) {
		$this->mysqlLink = $mysqlLink;
	}
	
	function execute( $inSql, $args ) {
		$sql = Parameterizer::getInstance()->parameterize( $inSql, $args );
		$mySqlResult = mysql_query( $sql, $this->mysqlLink );
		$rows = array();
		if( $mysqlResult === false ) {
			throw new SQLError( mysql_error($this->mysqlLink), $sql );
		} else if( is_resource($mysqlResult) ) {
			while( ($row = mysql_fetch_assoc($mysqlResult)) !== false ) {
				$rows[] = $row;
			}
		} 
		$affectedRowCount = mysql_affected_rows($this->mysqlLink);
		return new BasicSQLResult( $affectedRowCount, $rows );
	}
}
