<?php

use Classes\Router;

require 'vendor/autoload.php';

$router = new Router();

$router->define([
    'GET' => 'Controllers\PagesController@getRequest',
    'POST' => 'Controllers\PagesController@postRequest',
    'PUT' => 'Controllers\PagesController@otherRequest',
    'DELETE' => 'Controllers\PagesController@otherRequest',
    'PATCH' => 'Controllers\PagesController@otherRequest',
]);

$postBody = file_get_contents("php://input");

$router->direct($_SERVER['REQUEST_METHOD']);
