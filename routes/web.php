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

    //customer master 
    $router->group(['prefix' => 'customers'], function () use ($router) {
    $router->get('all', 'CustomerController@showAllCustomer');
    $router->get('view/{id}', 'CustomerController@showOneCustomer');
    $router->get('get-customers', 'CustomerController@getAllCustomer');
    $router->post('create', 'CustomerController@create');
    $router->post('update/{id}', 'CustomerController@update');
    $router->get('delete/{id}', 'CustomerController@delete');
    });

    //Lead Master
    $router->group(['prefix' => 'leads'], function () use ($router) {
    $router->get('all', 'LeadsController@showAllLeads');
    $router->get('view/{id}', 'LeadsController@showOneLead');
    $router->post('create', 'LeadsController@create');
    $router->post('update/{id}', 'LeadsController@update');
    $router->get('delete/{id}', 'LeadsController@delete');
    });

    //leads followups
    $router->group(['prefix' => 'leads-followups'], function () use ($router) {
    $router->get('all', 'LeadsFollowupsController@showAllLeadFollowups');
    $router->get('view/{id}', 'LeadsFollowupsController@showOneLeadFollowups');
    $router->get('get-leads', 'CustomerController@getAllLeads');
    $router->post('create', 'LeadsFollowupsController@create');
    $router->post('update/{id}', 'LeadsFollowupsController@update');
    $router->get('delete/{id}', 'LeadsFollowupsController@delete');
    });

    //quotation 
    $router->group(['prefix' => 'quotation'], function () use ($router) {
    $router->get('all', 'QuotationController@showAllQuotationHeader');
    $router->get('header/view/{id}', 'QuotationController@showOneQuotationHeader');
    $router->get('details/view/{id}', 'QuotationController@showQuotationDetails');
    $router->post('create', 'QuotationController@create');
    $router->post('update/{id}', 'QuotationController@update');
    $router->get('delete/{id}', 'QuotationController@delete');
    });

    //customer orders
    $router->group(['prefix' => 'customer-orders'], function () use ($router) {
    $router->get('all', 'CustomerOrdersController@showAllCusOrdesHeader');
    $router->get('header/view/{id}', 'CustomerOrdersController@showOneCusOrderHeader');
    $router->get('details/view/{id}', 'CustomerOrdersController@showCusOrdersDetails');
    $router->post('create', 'CustomerOrdersController@create');
    $router->post('update/{id}', 'CustomerOrdersController@update');
    $router->get('delete/{id}', 'CustomerOrdersController@delete');
    });
   




});
