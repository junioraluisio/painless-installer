<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 23/06/2018
 * Time: 07:35
 */

namespace App\Services\PasswordRecover;

use Slim\Container;
use Slim\Http\Request;

class PasswordUpdateService
{
    /**
     * @var \Slim\Container
     */
    protected $container;
    /**
     * @var \Pandora\Database\DataManager
     */
    private $dmPasswordRecover;
    /**
     * @var \Pandora\Database\DataManager
     */
    private $dmUser;
    /**
     * @var \App\Actions\passwordUpdateActions
     */
    private $passwordUpdateActions;
    
    /**
     * @var \UserAgentParser\Provider\PiwikDeviceDetector
     */
    private $userAgentParser;
    
    /**
     * PasswordRecoverService constructor.
     *
     * @param \Slim\Container $container
     *
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __construct(Container $container)
    {
        $this->container              = $container;
        $this->passwordUpdateActions = $container->get('passwordUpdateActions');
        $this->dmPasswordRecover      = $container->get('dmPasswordRecover');
        $this->dmUser                 = $container->get('dmUsers');
        $this->userAgentParser        = $container->get('userAgentParser');
    }
    
    /**
     * @param \Slim\Http\Request $request
     *
     * @return string
     */
    public function update(Request $request)
    {
        $ret['sts'] = false;
        $ret['msg'] = '';
        
        $password         = $request->getParam('password');
        $password_confirm = $request->getParam('password_confirm');
        $token            = $request->getParam('token');
        
        $verify = $this->verifyPasswords($password, $password_confirm);
        
        if ($verify['sts']) {
            $validate = $this->validatePassword($password);
            
            if ($validate['sts']) {
                $recover = $this->dmPasswordRecover->findByFieldsValues(['token' => $token], 1);
                
                $id = $recover['recover_id'] ?? '';
                
                $dateNow = new \DateTime(date('Y-m-d H:i:s'));
                
                $parameters = ['ipt_id'=>$id];
                
                $opPasswordRecover = $this->passwordUpdateActions->update($parameters);
                
                $ret['msg'] = $opPasswordRecover;
                
                //$ret['msg'] = 'Senha alterada com sucesso!';
            } else {
                $ret['msg'] = $validate['msg'];
            }
        } else {
            $ret['msg'] = $verify['msg'];
        }
        
        return json_encode($ret);
    }
    
    private function verifyPasswords(string $password, string $confirm)
    {
        $ret['sts'] = false;
        
        if (empty($password)) {
            $ret['msg'] = 'Digite uma senha!';
        } else {
            if (empty($confirm)) {
                $ret['msg'] = 'Confirme a senha digitada!';
            } else {
                if ($password === $confirm) {
                    $ret['sts'] = true;
                } else {
                    $ret['msg'] = 'Os valores dos campos não conferem!';
                }
            }
        }
        
        return $ret;
    }
    
    private function validatePassword(string $password)
    {
        $ret['sts'] = true;
        
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $number    = preg_match('/[0-9]/', $password);
        
        if (!$uppercase) {
            $ret['sts'] = false;
            $ret['msg'] = 'A senha deve ter ao menos um caractere em letras maiúsculas!';
        }
        
        if (!$lowercase) {
            $ret['sts'] = false;
            $ret['msg'] = 'A senha deve ter ao menos um caractere em minúsculo!';
        }
        
        if (!$number) {
            $ret['sts'] = false;
            $ret['msg'] = 'A senha deve ter pelo menos um número!';
        }
        
        if (strlen($password) < 8) {
            $ret['sts'] = false;
            $ret['msg'] = 'A senha deve ter no mínimo oito caracteres!';
        }
        
        return $ret;
    }
}