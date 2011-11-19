<?php

class TOGoS_DBC_MySQL_MySQLExecutor implements TOGoS_DBC_SQLExecutor
{
	public static function createFromConfig( $conf ) {
		return new TOGoS_DBC_MySQL_MySQLExecutor( $conf );
	}
	
	public static function connect( $conf ) {
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
			throw new Kap_CartLib_DBC_DBConnectionException( "Could not connect to MySQL $user@$host: ".mysql_error() );
		}
		if( $charset = @$conf['charset'] ) {
			mysql_set_charset( $charset, $mysqlLink );
		}
		if( $db = @$conf['database'] ) {
			mysql_select_db( $db, $mysqlLink );
		}
		return $mysqlLink;
	}
	
	////

	protected $config;
	protected $mysqlLink;
	protected $parameterizer;
	protected $queryListeners = array();
	
	public function __construct( $config ) {
		if( is_resource($config) ) {
			$this->mysqlLink = $config;
		} else {
			$this->config = $config;
		}
	}
	
	public function getConnection() {
		if( $this->mysqlLink === null ) {
			$this->mysqlLink = self::connect( $this->config );
		}
		return $this->mysqlLink;
	}
	
	protected function getParameterizer() {
		if( $this->parameterizer === null ) {
			$this->parameterizer = new TOGoS_DBC_MySQL_MySQLParameterizer($this->getConnection());
		}
		return $this->parameterizer;
	}
		
	public function execute( $inSql, array $args=array() ) {
		$sql = $this->getParameterizer()->parameterize( $inSql, $args );
		foreach( $this->queryListeners as $l ) {
			call_user_func( $l, $inSql, $args, $sql );
		}
		$link = $this->getConnection();
		$mysqlResult = mysql_query( $sql, $link );
		if( $mysqlResult === false ) {
			throw new TOGoS_DBC_SQLException( mysql_error($link), $sql );
		}
		$rows = array();
		if( is_resource($mysqlResult) ) {
			while( ($row = mysql_fetch_assoc($mysqlResult)) !== false ) {
				$rows[] = $row;
			}
		} 
		$affectedRowCount = mysql_affected_rows($link);
		return new TOGoS_DBC_Util_BasicSQLResult( $affectedRowCount, $rows );
	}
	
	/** Interface for this may change - don't depend on it. */
	public function addQueryListener( $f ) {
		$this->queryListeners[] = $f;
	}
}
