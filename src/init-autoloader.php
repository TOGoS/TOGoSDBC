<?php

function __autoload($className) {
	require_once str_replace('\\','/',$className).'.php';
}
