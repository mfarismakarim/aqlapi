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
use App\Mail\DonationMail;
use Illuminate\Support\Facades\Mail;

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/email', function () use ($router) {
    $banks = [
        [
            "bank_name" => "Bank Jabar",
            "account_number" => "567891021221",
            "owner" => "John Doe" ] 
    ];
    $data = [
        'campaign' => "Tebar Rahmat Allah Setiap Hari Dengan Sedekah Awal Waktu",
        'name' => "Doe John",
        'amount' => number_format(750000,0,',','.'),
        'banks' => $banks,
        'image_url' => "https://res.cloudinary.com/aql-peduli/image/upload/v1608630934/Mandiri_5151_cropped_8bb2d93c43.jpg"
    ];
    return new DonationMail($data); 
});

$router->get('/getPaidDonation', 'MainController@getPaidDonation');
$router->get('/getDonation', 'MainController@getDonation');
$router->get('/getCampaign', 'MainController@getCampaign');
$router->post('/addDonation', 'MainController@addDonation');
$router->post('/addCampaign', 'MainController@addCampaign');
$router->put('/setPaidDonation', 'MainController@setPaidDonation');
$router->delete('/deleteDonation', 'MainController@deleteDonation');
$router->delete('/deleteCampaign', 'MainController@deleteCampaign');


$router->post('/login', 'JwtController@login');
$router->post('/save', 'JwtController@save');
$router->group(['middleware' => 'auth'], function ($router) {
    $router->get('/test', 'JwtController@test'); 
    $router->post('/me', 'JwtController@me'); 
    $router->post('/logout', 'JwtController@logout'); 

    $router->get('/getPaidDonationAuth', 'MainController@getPaidDonationAuth');
    $router->get('/getDonationAuth', 'MainController@getDonationAuth');
    $router->get('/getCampaignAuth', 'MainController@getCampaignAuth');
    $router->post('/addDonationAuth', 'MainController@addDonationAuth');
    $router->post('/addCampaignAuth', 'MainController@addCampaignAuth');
    $router->put('/setPaidDonationAuth', 'MainController@setPaidDonationAuth');
    $router->delete('/deleteDonationAuth', 'MainController@deleteDonationAuth');
    $router->delete('/deleteCampaignAuth', 'MainController@deleteCampaignAuth');
}); 