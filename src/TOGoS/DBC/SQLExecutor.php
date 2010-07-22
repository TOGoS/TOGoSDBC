<?php

namespace TOGoS\DBC;

interface SQLExecutor {
	public function execute( $sql, $args );
}
