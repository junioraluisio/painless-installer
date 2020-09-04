<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 02/08/2017
 * Time: 07:03
 */

namespace App\Controllers\Auth;

class AuthController
{
    protected $container;
    
    // constructor receives container instance
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function login()
    {
        $authenticate = $this->container->authenticate;
        
        $auth = $authenticate->getin();
        
        $verify = $auth['verify'] ?? false;
        
        if ($verify) {
            $_SESSION[$_ENV['SESSION_NAME']]['auth'] = true;
            $_SESSION[$_ENV['SESSION_NAME']]['user'] = $auth;
            
            return json_encode([
                'status'  => 'success',
                'message' => 'Login efetivado com sucesso!'
            ]);
        } else {
            session_unset();
            session_destroy();
            
            return json_encode([
                'status'  => 'error',
                'message' => 'Login ou senha inv&aacute;lido!'
            ]);
        }
    }
}