<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 17/03/2018
 * Time: 14:24
 */
return [
    // Informações de conexão com o banco de dados
    'JWT_PATH_PROTECTED'   => isset($_ENV['JWT_PATH_PROTECTED']) ? explode('|', $_ENV['JWT_PATH_PROTECTED']) : explode('|', getenv('JWT_PATH_PROTECTED')),
    'JWT_PATH_PASSTHROUGH' => isset($_ENV['JWT_PATH_PASSTHROUGH']) ? explode('|', $_ENV['JWT_PATH_PASSTHROUGH']) : explode('|', getenv('JWT_PATH_PASSTHROUGH'))
];