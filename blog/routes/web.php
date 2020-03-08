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

//страница блога
Route::get('/', 'HomeController@index');

// страница поста
Route::get('/post/{slug}', 'HomeController@show')->name('post.show');

// страница выборки постов по тегам
Route::get('/tag/{slug}', 'HomeController@tag')->name('tag.show');

// страница выборки постов по категориям
Route::get('/category/{slug}', 'HomeController@category')->name('category.show');

Route::group(['middleware' => 'guest'], function(){
	// форма регистрации пользователя
	Route::get('/registration', 'AuthController@registrationForm');
	// регистрация пользователя
	Route::post('/registration', 'AuthController@registration');
	// форма входа 
	Route::get('/login', 'AuthController@loginForm')->name('login');
	// вход
	Route::post('/login', 'AuthController@login');
});

Route::group(['middleware' => 'auth'], function(){
	// выход
	Route::get('/logout', 'AuthController@logout');
	// форма профиля
	Route::get('/profile', 'ProfileController@index');
	// изменение данных фрофиля
	Route::post('/profile', 'ProfileController@store');
	// комментарий пользователя
	Route::post('/comment', 'CommentsController@store');
});

// группировка роутов для admin, сокращение префиксов и неймспейсов
Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>'admin'], function(){
	Route::get('/', 'DashboardController@index');
	Route::resource('/categories', 'CategoriesController');
	Route::resource('/tags', 'TagsController');
	Route::resource('/users', 'UsersController');
	Route::resource('/subs', 'SubscribersController');
	Route::get('/comments', 'CommentsController@index');
	Route::get('/comments/toggle/{id}', 'CommentsController@toggle');
	Route::delete('/comments/{id}/destroy', 'CommentsController@destroy')->name('comments.destroy');
	Route::resource('/posts', 'PostsController');
});

