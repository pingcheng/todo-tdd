<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('api/user/info', 'UserApiController@info')->name('user.api.info');

Route::post('api/todo', 'TodoListController@store')->name('todo.api.add');
Route::patch('api/todo/{todo}', 'TodoListController@update')->name('todo.api.update');
Route::delete('api/todo/{todo}', 'TodoListController@delete')->name('todo.api.delete');