<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/'], function ($router) {
    $router->get('login/','UserController@authenticate');
    $router->post('todo/','TodoListController@store');
    $router->get('todo/', 'TodoListController@index');
    $router->get('todo/{id}/', 'TodoListController@show');
    $router->put('todo/{id}/', 'TodoListController@update');
    $router->delete('todo/{id}/', 'TodoListController@destroy');
    $router->post('todo/sync', 'TodoListController@sync');
});
