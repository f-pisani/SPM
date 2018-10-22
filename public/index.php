<?php
require_once '../autoload.php';
require_once '../bootstrap.php';

use Lib\Route;

Route::any('/login', 'AuthController@index');
Route::any('/logout', 'AuthController@logout');

Route::execute();
