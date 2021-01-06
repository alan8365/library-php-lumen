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

// auth router

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


// book router

/** @var Router $router */
$router->group([
    'prefix' => 'book',
    'namespace' => 'Api',
    'middleware' => 'cors'
], function () use ($router) {
    // Through by auth
    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {
        $router->get('favorite', 'BookController@listFavorite');
        $router->post('favorite/{isbn}', 'BookController@setFavorite');
    });

    // Through by book-write
    $router->group([
        'middleware' => ['auth', 'rbac:book-write']
    ], function () use ($router) {
        $router->post('', 'BookController@store');
        $router->delete('{isbn:\d+}', 'BookController@remove');
    });

    $router->get('', 'BookController@list');
    $router->get('{isbn:\d+}', 'BookController@detail');
    $router->get('search', 'BookController@search');
});
