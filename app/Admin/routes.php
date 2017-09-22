<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('article', "ArticleController");
    $router->resource('cat', "CatController");
    $router->resource('users', "UsersController");
    $router->resource('album', "AlbumController");
    $router->get('image/{id}', 'ImageController@index');
    $router->get('image/{albumId}/create', 'ImageController@create');
    $router->post('image/save', 'ImageController@save');
    $router->get('image/{albumId}/edit/{id}', 'ImageController@edit');
    $router->post('image/update/{id}', 'ImageController@update');
    $router->get('image/{albumId}/destory/{id}', 'ImageController@destory');
    $router->get('image/album/get/{id}', 'AlbumController@getAlbumImage');
    $router->get('image/get/{id}', 'ImageController@getImage');

});
