<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 03/06/2018
 * Time: 17:43
 */

namespace App\Controllers\PasswordRecover;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Twig_Environment;

class PasswordRecoverController
{
    /**
     * @var \Slim\Container
     */
    protected $container;
    
    /**
     * @var Twig_Environment
     */
    var $twig;
    /**
     * @var \Pandora\Database\DataManager
     */
    private $dmPasswordRecover;
    
    /**
     * PasswordRecoverController constructor.
     *
     * @param \Slim\Container $container
     *
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __construct(Container $container)
    {
        $this->twig              = $container->get('twig');
        $this->dmPasswordRecover = $container->get('dmPasswordRecover');
    }
    
    /**
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function password_recover(Request $request, Response $response)
    {
        $page = 'password_recover.html';
        
        $load = $this->twig->load($page);
        
        $vars['path_web'] = CONFIG['PATH_WEB'];
        
        $response->getBody()->write($load->render($vars));
        
        return $response;
    }
    
    /**
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function password_new(Request $request, Response $response)
    {
        $page = 'errors/expired_link.html';
        
        $token = $request->getAttribute('token');
        
        $decryptToken = krypt('decrypt', $token);
        
        $arrToken = json_decode($decryptToken, true);
        
        $dateLink = isset($arrToken['exp']) ? new \DateTime($arrToken['exp']) : '';
        
        $dateNow = new \DateTime(date('Y-m-d H:i:s'));
        
        $verify = $this->verify($token);
        
        if ($verify) {
            $page = 'password_new.html';
            
            $diff = $dateLink->diff($dateNow);
            
            $horas = $diff->h + ($diff->days * 24);
            
            if ($horas >= 24) {
                $page = 'errors/expired_link.html';
            }
        }
        
        $load = $this->twig->load($page);
        
        $vars['path_web'] = CONFIG['PATH_WEB'];
        $vars['token']    = $token;
        
        $response->getBody()->write($load->render($vars));
        
        return $response;
    }
    
    
    private function verify(string $token)
    {
        $fieldsValues['token']     = $token;
        $fieldsValues['condition'] = 'A';
        
        $status = $this->dmPasswordRecover->findByFieldsValues($fieldsValues, 1);
        
        if (!$status) {
            return false;
        }
        
        return true;
    }
}