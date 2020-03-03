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

Route::get('/', 'HomeController@index');

// группировка роутов, сокращение префиксов и неймспейсов
Route::group(['prefix'=>'admin', 'namespace'=>'Admin'], function(){
	Route::get('/', 'DashboardController@index');
	Route::resource('/categories', 'CategoriesController');
	Route::resource('/tags', 'TagsController');
	Route::resource('/users', 'UsersController');
	Route::resource('/subs', 'SubscribersController');
	Route::resource('/comments', 'CommentsController');
	Route::resource('/posts', 'PostsController');
});

