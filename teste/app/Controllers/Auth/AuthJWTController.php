<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 02/08/2017
 * Time: 07:03
 */

namespace App\Controllers\Auth;

class AuthJWTController
{
    protected $container;
    
    // constructor receives container instance
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function login()
    {
        $authenticate = $this->container->jwtAuthenticate;
        
        $auth = $authenticate->getin();
        
        $verify = $auth['verify'] ?? false;
        
        if ($verify) {
            $jwtBuilder = $this->container->jwtBuilder;
            
            $token = $jwtBuilder->setIssuer(CONFIG['JWT_ISSUER'])
                                ->setAudience(CONFIG['JWT_AUDIENCE'])
                                ->setId(CONFIG['JWT_ID'], true)
                                ->setIssuedAt(CONFIG['JWT_ISSUEAT'])
                                ->setNotBefore(CONFIG['JWT_NOTBEFORE'])
                                ->setExpiration(CONFIG['JWT_EXPIRATION'])
                                ->set('scope', $auth)
                                ->sign($this->container->jwtSigner, CONFIG['JWT_SECRET'])
                                ->getToken();
                        
            return json_encode([
                'status'  => 'success',
                'message' => 'Login efetuado com sucesso!',
                'token'   => 'Bearer ' . $token
            ]);
        } else {
            return json_encode([
                'status'  => 'error',
                'message' => 'Login ou senha inv&aacute;lido!',
                'token'   => ''
            ]);
        }
    }
    
    public function refresh()
    {
        $auth = $this->container->jwt->scope;
        
        $jwtBuilder = $this->container->jwtBuilder;
        
        $token = $jwtBuilder->setIssuer(CONFIG['JWT_ISSUER'])
                            ->setAudience(CONFIG['JWT_AUDIENCE'])
                            ->setId(CONFIG['JWT_ID'], true)
                            ->setIssuedAt(CONFIG['JWT_ISSUEAT'])
                            ->setNotBefore(CONFIG['JWT_NOTBEFORE'])
                            ->setExpiration(CONFIG['JWT_EXPIRATION'])
                            ->set('scope', $auth)
                            ->sign($this->container->jwtSigner, CONFIG['JWT_SECRET'])
                            ->getToken();
        
        return print('Bearer ' . $token);
    }
}