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

Route::get('user/info', 'UserApiController@info')->name('user.api.info');

Route::get('todo', 'TodoListController@list')->name('todo.api.list');
Route::post('todo', 'TodoListController@store')->name('todo.api.add');
Route::post('todo/{todo}/done', 'TodoListController@done')->name('todo.api.done');
Route::post('todo/{todo}/undone', 'TodoListController@undone')->name('todo.api.undone');
Route::patch('todo/{todo}', 'TodoListController@update')->name('todo.api.update');
Route::delete('todo/{todo}', 'TodoListController@delete')->name('todo.api.delete');