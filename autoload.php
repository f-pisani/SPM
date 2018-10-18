<?php
/***********************************************************************************************************************
 * spl_autoload_register is case sensitive : namespace and class name must match a valid path
 */
spl_autoload_register(function($class_path){
	$class_path = str_replace('\\', '/', $class_path);

	$autoload_path = '../'. $class_path  .".php";

	if(file_exists($autoload_path))
		require_once($autoload_path);
});sqd
