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
$router->group([
    'prefix' => 'auth',
    'namespace' => 'Api',
], function () use ($router) {
    $router->post('/store', 'UserController@store');
    $router->post('/login', 'UserController@login');
    $router->get('/user', 'UserController@index');
    $router->get('/me', 'UserController@me');
    $router->get('/logout', 'UserController@logout');
});
