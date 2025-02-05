<?php

if(file_exists(__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php')) {
	include_once(__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
}

spl_autoload_register(function($class) {
	$file = __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, array_slice(explode('\\', $class ), 0, -1)).DIRECTORY_SEPARATOR.implode('' , array_slice( explode( '\\' , $class ), -1 , 1)).'.php';
	if(file_exists($file)) {
		include($file);
	}
});