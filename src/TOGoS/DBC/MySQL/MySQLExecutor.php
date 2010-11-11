<?php

class TOGoS_DBC_MySQLExecutor implements TOGoS_DBC_SQLExecutor
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
			throw new TOGoS_DBC_DBConnectionException( "Could not connect to MySQL $user@$host: ".mysql_error() );
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
	protected $queryListeners = array();
	
	public function __construct( $mysqlLink ) {
		$this->mysqlLink = $mysqlLink;
	}
	
	public function execute( $inSql, array $args=array() ) {
		$sql = MySQLParameterizer::getInstance()->parameterize( $inSql, $args );
		foreach( $this->queryListeners as $l ) {
			call_user_func( $l, $inSql, $args, $sql );
		}
		$mysqlResult = mysql_query( $sql, $this->mysqlLink );
		if( $mysqlResult === false ) {
			throw new TOGoS_DBC_SQLException( mysql_error($this->mysqlLink), $sql );
		}
		$rows = array();
		if( is_resource($mysqlResult) ) {
			while( ($row = mysql_fetch_assoc($mysqlResult)) !== false ) {
				$rows[] = $row;
			}
		} 
		$affectedRowCount = mysql_affected_rows($this->mysqlLink);
		return new TOGoS_DBC_Util_BasicSQLResult( $affectedRowCount, $rows );
	}
	
	/** Interface for this may change - don't depend on it. */
	public function addQueryListener( $f ) {
		$this->queryListeners[] = $f;
	}
}
