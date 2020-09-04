<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 11/03/2018
 * Time: 08:48
 * @see https://medium.com/@fidelissauro/slim-framework-criando-microservices-07-implementando-seguran%C3%A7a-b%C3%A1sica-com-http-auth-e-proxy-ed6dd6d517f4
 */

$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret"      => $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET'),
    // Cobertura da autenticação, no caso de "/" protege todos os links
    "path"        => $_ENV['JWT_PATH_PROTECTED'] ?? getenv('JWT_PATH_PROTECTED'),
    // Links livres da verificação da autenticação
    "passthrough" => $_ENV['JWT_PATH_PASSTHROUGH'] ?? getenv('JWT_PATH_PASSTHROUGH'),
    // Se tudo der certo dispara o callback
    "callback"    => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    },
    // Se der errado dispara o erro
    "error"       => function ($request, $response, $arguments) {
        $vars = [
            'status'  => 'error',
            'message' => $arguments["message"]
        ];
        
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($vars, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

include CONFIG['PATH_ROUTES'] . DS . 'Auth' . DS . 'AuthJWTRoutes.php';