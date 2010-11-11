<?php

function __autoload($className) {
	require_once str_replace(array('\\','_'),'/',$className).'.php';
	$usClassName = str_replace('\\','_',$className);
	if( !class_exists($className,false) && class_exists($usClassName,false) ) {
		class_alias($usClassName,$className);
	}
}
