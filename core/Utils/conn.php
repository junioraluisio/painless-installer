<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 22/03/2017
 * Time: 07:08
 */
use Amjr\Factory\Connection\Conn;

// Informações de conexão com o banco de dados
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);

$conn = new Conn(DB_NAME, DB_HOST, DB_USER, DB_PASS);