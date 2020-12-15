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
$router->group(['prefix' => 'api'], function () use ($router) {

    //item master 
    $router->group(['prefix' => 'items'], function () use ($router) {
    $router->post('all', 'ItemController@showAllItems');
    $router->get('view/{id}', 'ItemController@showOneItems');
    $router->get('get-customers', 'ItemController@getAllItems');
    $router->post('create', 'ItemController@create');
    $router->post('update/{id}', 'ItemController@update');
    $router->get('delete/{id}', 'ItemController@delete');
    });

    //item categories Master
    $router->group(['prefix' => 'item-categories'], function () use ($router) {
    $router->post('all', 'ItemCategoriesController@showAllCategories');
    $router->get('view/{id}', 'ItemCategoriesController@showOneCategories');
    $router->get('get-categories', 'ItemCategoriesController@getAllCategories');
    $router->post('create', 'ItemCategoriesController@create');
    $router->post('update/{id}', 'ItemCategoriesController@update');
    $router->get('delete/{id}', 'ItemCategoriesController@delete');
    });

    //employee Master
    $router->group(['prefix' => 'employee'], function () use ($router) {
    $router->post('all', 'EmployeeController@showAllEmployee');
    $router->get('view/{id}', 'EmployeeController@showOneEmployee');
    $router->get('get-employee', 'EmployeeController@getAllEmployee');
    $router->post('create', 'EmployeeController@create');
    $router->post('update/{id}', 'EmployeeController@update');
    $router->get('delete/{id}', 'EmployeeController@delete');
    });

});
