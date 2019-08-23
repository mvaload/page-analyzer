<?php

use Barryvdh\Debugbar\Facade as Debugbar;

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
$router->get('/', ['as' => 'index.show', 'uses' => 'IndexController@show']);
$router->post('/domains', ['as' => 'domains.store', 'uses' => 'DomainsController@store']);
$router->get('/domains', ['as' => 'domains.index', 'uses' => 'DomainsController@index']);
$router->get('/domains/{id}', ['as' => 'domains.show', 'uses' => 'DomainsController@show']);
