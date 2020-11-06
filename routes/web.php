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
use Illuminate\Support\Facades\Route;


/** @var Route $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

/** @var Route $router */
$router->get('/user', function () use ($router) {
    return \App\User::all();
});
