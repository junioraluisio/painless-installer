<?php
/**
 * Created by Invoker.
 * Author: Aluisio Martins Junior <junior@mjpsolucoes.com.br>
 * Date: 15/08/2017
 * Time: 14:08
*/

use Pandora\Database\DataManager;
use App\Entities\Auth\Users\Users;

$container['dmUsers'] = function ($c) {
    $users = new Users();

    return new DataManager($c['conn'], $users);
};

$app->group('/users', function () {
    $this->map(['PATCH'], '/{id}', \App\Actions\UsersActions::class . ':enable');
    $this->map(['DELETE'], '/{id}', \App\Actions\UsersActions::class . ':disable');
    $this->map(['PUT'], '/{id}', \App\Actions\UsersActions::class . ':update');
    $this->map(['POST'], '', \App\Actions\UsersActions::class . ':insert');
});

//Rotas extras
include 'UsersRoutesExtra.php';