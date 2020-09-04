<?php
/**
 * Created by Invoker.
 * Author: Aluisio Martins Junior <junior@mjpsolucoes.com.br>
 * Date: 10/06/2018
 * Time: 12:06
 */

use App\Entities\Auth\PasswordRecover\PasswordRecover;
use Pandora\Database\DataManager;

$container['dmPasswordRecover'] = function ($c) {
    $passwordRecover = new PasswordRecover();
    
    return new DataManager($c['conn'], $passwordRecover);
};

$app->group('/password/recover', function () {
    $this->map(['PATCH'], '/{id}', \App\Actions\PasswordRecoverActions::class . ':enable');
    $this->map(['DELETE'], '/{id}', \App\Actions\PasswordRecoverActions::class . ':disable');
    $this->map(['PUT'], '/{id}', \App\Actions\PasswordRecoverActions::class . ':update');
    $this->map(['POST'], '', \App\Actions\PasswordRecoverActions::class . ':insert');
});

//Rotas extras
include 'PasswordRecoverRoutesExtra.php';