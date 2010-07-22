<?php

function __autoload($className) {
	require_once str_replace('\\','/',$className).'.php';
	$usClassName = str_replace('\\','_',$className);
	if( !class_exists($className,false) && class_exists($usClassName,false) ) {
		class_alias($usClassName,$className);
	}
}
