<?php
session_start();

use lib\Config;

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

Config::set('COLORS', ['f44336', 'e91e63', '9c27b0', '673ab7', '3f51b5', '2196f3', '03a9f4', '00bcd4', '009688',
                       '4caf50', '8bc34a', 'cddc39', 'ffeb3b', 'ffc107', 'ff9800', 'ff5722', '795548', '9e9e9e',
				       '607d8b']);
