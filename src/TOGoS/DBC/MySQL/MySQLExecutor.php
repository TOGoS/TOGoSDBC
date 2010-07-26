<?php

namespace TOGoS\DBC\MySQL;

use TOGoS\DBC\SQLExecutor;
use TOGoS\DBC\SQLException;
use TOGoS\DBC\Util\Parameterizer;
use TOGoS\DBC\Util\BasicSQLResult;

class MySQLExecutor implements SQLExecutor
{
	public static function createFromConfig( $conf ) {
		$host = $conf['host'];
		if( $port = $conf['port'] ) {
			$host .= ":$port";
		}
		$user = @$conf['user'];
		$pass = @$conf['password'];
		$newLink = @$conf['new-link'];
		$flags = @$conf['flags'] or $flags = 0;
		$mysqlLink = mysql_connect( $host, $user, $pass, $newLink, $flags );
		if( $mysqlLink === false ) {
			throw new DBConnectionException( "Could not connect to MySQL $user@$host: ".mysql_error() );
		}
		if( $charset = @$conf['charset'] ) {
			mysql_set_charset( $charset, $mysqlLink );
		} 
		if( $db = @$conf['database'] ) {
			mysql_select_db( $db, $mysqlLink );
		}
		return new MySQLExecutor($mysqlLink);
	}
	
	////

	protected $mysqlLink;
	
	function __construct( $mysqlLink ) {
		$this->mysqlLink = $mysqlLink;
	}
	
	function execute( $inSql, $args ) {
		$sql = Parameterizer::getInstance()->parameterize( $inSql, $args );
		$mysqlResult = mysql_query( $sql, $this->mysqlLink );
		if( $mysqlResult === false ) {
			throw new SQLException( mysql_error($this->mysqlLink), $sql );
		}
		$rows = array();
		if( is_resource($mysqlResult) ) {
			while( ($row = mysql_fetch_assoc($mysqlResult)) !== false ) {
				$rows[] = $row;
			}
		} 
		$affectedRowCount = mysql_affected_rows($this->mysqlLink);
		return new BasicSQLResult( $affectedRowCount, $rows );
	}
}
