<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 18/05/2017
 * Time: 07:06
 */

return [
    // Informações de conexão com o banco de dados
    'DB_NAME' => $_ENV['DB_NAME'] ?? getenv('DB_NAME'),
    'DB_HOST' => $_ENV['DB_HOST'] ?? getenv('DB_HOST'),
    'DB_USER' => $_ENV['DB_USER'] ?? getenv('DB_USER'),
    'DB_PASS' => $_ENV['DB_PASS'] ?? getenv('DB_PASS')
];