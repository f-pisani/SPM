<?php
session_start();

use Lib\Config;

/***********************************************************************************************************************
 * Database configuration
 */
Config::set('DB_HOST', 'localhost');
Config::set('DB_USER', 'root');
Config::set('DB_PWD', '');
Config::set('DB_BASE', 'kanban');


/***********************************************************************************************************************
 * Base URL of the APP
 */
Config::set('BASE_URL', 'http://spm:8080/');
