<?php
require_once '../autoload.php';
require_once '../bootstrap.php';

use lib\Route;

Route::any('/login', 'AuthController@index');
Route::any('/logout', 'AuthController@logout');

Route::any('/dashboard', 'KanbanController@index');
Route::any('/board/{id}', 'KanbanController@board')->where(['id' => '[0-9]+']);
Route::any('/board/{id}/settings', 'KanbanController@settings')->where(['id' => '[0-9]+']);
Route::any('/board/{id}/invitations', 'KanbanController@invitations')->where(['id' => '[0-9]+']);

Route::post('/api/board/create', 'ApiController@createBoard');

Route::execute();
