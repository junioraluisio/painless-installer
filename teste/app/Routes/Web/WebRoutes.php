<?php
session_start();

/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 30/03/2018
 * Time: 17:00
 */

use App\Middlewares\AuthMiddleware;
use App\Middlewares\WebMenuMiddleware;

$menus = [
    0 => ['url' => 'home', 'normal' => 'Home', 'uppercase' => 'HOME', 'status' => ''],
    1 => ['url' => 'login', 'normal' => 'Login', 'uppercase' => 'LOGIN', 'status' => '']
];


$app->any('/session/load', function ($request, $response, $args) use ($container) {
    //$container = $this->getContainer();
    //print_r(CONFIG['PATH_WEB']);//['PATH_ROUTES'];// $_SESSION['painless']['auth'] ?? 'Não criado!';
    echo $_SESSION[$_ENV['SESSION_NAME']]['auth'] ?? false;
});

$app->any('/[{page}]', \App\Controllers\Web\WebController::class . ':render')
    ->add(new WebMenuMiddleware($menus))
    ->add(new AuthMiddleware());