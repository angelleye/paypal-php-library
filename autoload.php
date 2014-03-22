<?php

// spl_autoload_register(function ($classname) {
// 	$classname = ltrim($classname, "\\");
// 	preg_match('/^(.+)?([^\\\\]+)$/U', $classname, $match);
// 	$classname = str_replace("\\", "/", $match[1])
// 	. str_replace(["\\", "_"], "/", $match[2])
// 	. ".php";
// 	include_once $classname;
// });

spl_autoload_register(function($class) {
	include __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.implode( DIRECTORY_SEPARATOR , array_slice( explode( '\\' , $class ) , 0 , -1 ) ) . DIRECTORY_SEPARATOR . str_replace( '_' , DIRECTORY_SEPARATOR , implode( '' , array_slice( explode( '\\' , $class ) , -1 , 1 ) ) ) . '.php';
});