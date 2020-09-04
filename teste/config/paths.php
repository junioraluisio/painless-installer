<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 16/07/2017
 * Time: 07:20
 */
return [
    // Informações dos caminhos da aplicação
    'PATH_ROOT' => dirname(path_root(),3),
    'PATH_WEB'  => dirname(path_web(),3),
    'PATH_ROUTES'  => dirname(path_root(),3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Routes',
    'PATH_CONTROLLERS'  => dirname(path_root(),3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers',
    'PATH_MIDDLEWARES'  => dirname(path_root(),3) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Middlewares'
];