<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 02/08/2017
 * Time: 07:01
 */

//$app->get('/login', \App\Controllers\AuthJWTController::class . ':login');

use App\Entities\Auth\Users\Users;
use Pandora\Auth\Authenticate;
use Pandora\Database\DataManager;

$container['authenticate'] = function ($c) {
    $users = new Users();
    $dm = new DataManager($c['conn'], $users);
    
    $args = $c->request->getParams();
    
    $login = $args['user'] ?? '';
    $pass  = $args['pass'] ?? '';
    
    return new Authenticate($dm, $login, $pass);
};

$app->group('/auth', function () {
    // Autentica e retorna um token JWT
    $this->map(['GET', 'POST'], '/login[/{login}[/{senha}]]', \App\Controllers\Auth\AuthController::class . ':login');
});