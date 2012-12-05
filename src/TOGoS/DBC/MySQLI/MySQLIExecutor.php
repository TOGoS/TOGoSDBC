<?php

class TOGoS_DBC_MySQLI_MySQLIExecutor implements TOGoS_DBC_SQLExecutor
{
	public static function createFromConfig( $conf ) {
		return new TOGoS_DBC_MySQLI_MySQLIExecutor( $conf );
	}
	
	public static function connect( $conf ) {
		$host = @$conf['host'] or $host = 'localhost';
		$port = @$conf['port'] or $port = 3306;
		$user = @$conf['user'];
		$pass = @$conf['password'];
		$socket = @$conf['socket'];
		$db = @$conf['database'] or $db = '';
		$newLink = @$conf['new-link'];
		if( !$newLink ) $host = "p:{$host}";
		$link = new mysqli( $host, $user, $pass, $db, $port, $socket );
		// Avoid $link->connect_error as it's reported to be, like most things in PHP, broken:
		if( mysqli_connect_error() ) {
			throw new Kap_CartLib_DBC_DBConnectionException(
				"Could not connect to MySQLI $user@$host: ".
				'#'.mysqli_connect_errno().' '.
				mysqli_connect_error()."; Settings = ".var_export($conf,true) );
		}
		if( $charset = @$conf['charset'] ) {
			$link->set_charset( $charset );
		}
		return $link;
	}
	
	////

	protected $config;
	protected $link;
	protected $parameterizer;
	protected $queryListeners = array();
	
	public function __construct( $config ) {
		if( is_resource($config) ) {
			$this->link = $config;
		} else {
			$this->config = $config;
		}
	}
	
	public function __destruct() {
		if( $this->link !== null ) {
			$this->link->close();
			unset($this->link);
		}
	}
	
	public function getConnection() {
		if( $this->link === null ) {
			$this->link = self::connect( $this->config );
		}
		return $this->link;
	}
	
	protected function getParameterizer() {
		if( $this->parameterizer === null ) {
			$this->parameterizer = new TOGoS_DBC_MySQLI_MySQLIParameterizer($this->getConnection());
		}
		return $this->parameterizer;
	}
	
	public function execute( $inSql, array $args=array() ) {
		$sql = $this->getParameterizer()->parameterize( $inSql, $args );
		foreach( $this->queryListeners as $l ) {
			call_user_func( $l, $inSql, $args, $sql );
		}
		$link = $this->getConnection();
		if( !$link->real_query( $sql ) ) {
			throw new TOGoS_DBC_SQLException( $link->error, $sql );
		}
		$result = $link->store_result();
		if( $link->error ) {
			throw new TOGoS_DBC_SQLException( 'Failed to store_result: '.$link->error, $sql );
		}
		// $rows = $result->fetch_all(MYSQLI_ASSOC); // Not available < PHP 5.3
		if( $result ) {
			for( $rows = array(); $row = $result->fetch_assoc(); $rows[] = $row );
			$result->free();
		} else {
			// This can happen if the query was not a SELECTing one.
			$rows = array();
		}
		$affectedRowCount = $link->affected_rows;
		$insertId = $link->insert_id;
		
		// Calling stored procedures for some reason generates an extra
		// recordset.  Since TOGoSDBC doesn't support multiple result
		// sets anyway, this will work around that:
		while( $link->more_results() ) {
			if( $link->next_result() && $extraResult = $link->store_result() ) {
				$extraResult->free();
			}
		}

		return new TOGoS_DBC_Util_BasicSQLResult( $rows, $affectedRowCount, $insertId );
	}
	
	/** Interface for this may change - don't depend on it. */
	public function addQueryListener( $f ) {
		$this->queryListeners[] = $f;
	}
}
