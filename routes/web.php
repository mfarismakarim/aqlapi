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

$router->get('/new', 'MainController@new');
$router->get('/getDonation', 'MainController@getDonation');
$router->get('/getPaidDonation', 'MainController@getPaidDonation');
$router->get('/getCampaign', 'MainController@getCampaign');
$router->post('/addDonation', 'MainController@addDonation');
$router->post('/addCampaign', 'MainController@addCampaign');
$router->post('/setPaidDonation', 'MainController@setPaidDonation');
