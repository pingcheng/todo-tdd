<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('todo', 'TodoListController@index')->name('todo.index');
Route::post('todo', 'TodoListController@store');
Route::patch('todo/{todo}', 'TodoListController@update');
Route::delete('todo/{todo}', 'TodoListController@delete');
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/handle','Auth\LoginController@handleProviderCallback');