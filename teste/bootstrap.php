<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 09/03/2017
 * Time: 06:28
 */
require 'vendor/autoload.php';

use Pandora\Config\Files;
use Pandora\Utils\ExtractFiles;

date_default_timezone_set('America/Sao_paulo');

define('DS', DIRECTORY_SEPARATOR);

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => false
    ]
]);

try {
    // configurações do arquivo .env
    $dotEnv = new Dotenv\Dotenv(__DIR__);
    $dotEnv->load();

    $extractFilesDir = new ExtractFiles('config/');

    $configFiles = new Files($extractFilesDir);

    $config = $configFiles->load();

    define('CONFIG', $config);

    include "vendor/painless/invoker/src/Pandora/Debug/Debug.php";

// Containers
    include 'containers.php';

// Routes
    include 'routes/auth.php';
    include 'routes/authJWT.php';
    include 'routes/app.php';
    include 'routes/api.php';
    include 'routes/web.php';

    $app->run();
} catch (Exception $e) {
    $config = null;
}