<?php

use App\Exceptions\ExampleErrorException;
use Laravel\Lumen\Routing\Router;

/** @var Router $router */

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

// Health Check
$router->get('healthz', function () {
    return response()->json(['status' => 'ok']);
});

$router->get('/exception', function () use ($router) {
    throw new ExampleErrorException(11, "Example Exception");
});
