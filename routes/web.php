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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/article/read/{id}', 'ArticleController@read');
Route::get('/article/index', 'ArticleController@index');

Route::post('/comment/save', 'CommentController@save');
Route::get('/comment/index', 'CommentController@index');

Route::get('/image/index', 'ImageController@index');
Route::get('/image/index/{albumId}', 'ImageController@getImageList');

Route::get('/image/album/get/{id}', 'ImageController@getAlbumImage');
Route::get('/image/get/{id}', 'ImageController@getImage');

Route::get('/test/index', 'TestController@index');

