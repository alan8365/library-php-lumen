<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use \Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->options('{any:.*}', ['middleware' => 'cors']);

/** @var Router $router */
$router->group([
    'prefix' => 'auth',
    'namespace' => 'Api',
    'middleware' => 'cors'
], function () use ($router) {
    $router->post('/store', 'UserController@store');
    $router->post('/login', 'UserController@login');
    $router->post('/logout', 'UserController@logout');

    $router->get('', 'UserController@index');
    $router->get('/whoAmI', 'UserController@whoAmI');
});


/** @var Router $router */
$router->group([
    'prefix' => 'book',
    'namespace' => 'Api',
    'middleware' => 'cors'
], function () use ($router) {
    $router->get('', 'BookController@list');
    $router->post('', 'BookController@store');
    $router->get('{isbn:\d+}', 'BookController@detail');
    $router->get('favorite', 'BookController@listFavorite');
    $router->post('favorite/{isbn}', 'BookController@setFavorite');
});
