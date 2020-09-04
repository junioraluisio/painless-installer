<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 09/06/2018
 * Time: 17:32
 */

namespace App\Services\PasswordRecover;


use Slim\Container;
use Slim\Http\Request;

class PasswordRecoverService
{
    /**
     * @var \Slim\Container
     */
    protected $container;
    
    /**
     * @var \Pandora\Database\DataManager
     */
    private $dataManager;
    
    /**
     * @var \App\Actions\PasswordRecoverActions
     */
    private $passwordRecoverActions;
    
    /**
     * @var \Pandora\Email\Send
     */
    private $sendMail;
    
    /**
     * @var \Pandora\Email\Templates
     */
    private $template;
    
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
        $this->passwordRecoverActions = $container->get('passwordRecoverActions');
        $this->sendMail               = $container->get('sendMail');
        $this->template               = $container->get('templateMail');
        $this->dataManager            = $container->get('dmUsers');
        $this->userAgentParser        = $container->get('userAgentParser');
    }
    
    /**
     * @param \Slim\Http\Request $request
     *
     * @return mixed
     */
    public function send(Request $request)
    {
        $email = $request->getParam('email');
        
        $info = $this->info($email);
        
        $userId   = $info['id'] ?? '';
        $userName = $info['name'] ?? '';
        
        $mail = [
            'box'  => $email,
            'name' => $userName
        ];
        
        $token = $this->token($userId);
        
        $subject = $_ENV['APP_NAME'] . utf8_decode(' - Recuperação de senha');
        
        $html = utf8_decode($this->template->load('password_recover_template.html'));
        
        $link = CONFIG['PATH_WEB'] . '/password/recover/new/' . $token;
        
        $arrSearchReplace = [
            'VAR_NAME_USER'    => $userName,
            'VAR_NAME_APP'     => $_ENV['APP_NAME'],
            'VAR_LINK_RECOVER' => $link
        ];
        
        $bodyHtml = $this->template->replace($arrSearchReplace, $html);
        
        $bodyNoHtml = $bodyHtml;
        
        $send = $this->sendMail->simple($mail, $subject, $bodyNoHtml, $bodyNoHtml);
        
        if ($send[0]) {
            $fields['ipt_user_id'] = $userId;
            $fields['ipt_email']   = $email;
            $fields['ipt_ip']      = $_SERVER["REMOTE_ADDR"];
            $fields['ipt_device']  = $this->device();
            $fields['ipt_token']   = $token;
            
            $this->passwordRecoverActions->insert($fields);
        }
        
        return $send[1] ?? 'Erro ao tentar enviar...';
    }
    
    private function device()
    {
        $str = $this->userAgentParser->getDevice()->getType();
        
        if (!empty($this->userAgentParser->getDevice()->getBrand())) {
            $str .= ', ' . $this->userAgentParser->getDevice()->getBrand();
        }
        
        if (!empty($this->userAgentParser->getDevice()->getModel())) {
            $str .= ' - ' . $this->userAgentParser->getDevice()->getModel();
        }
        
        return $str;
    }
    
    private function info($email)
    {
        $user = $this->dataManager->findByFieldsValues(['email' => $email], 1);
        
        $userId   = $user['user_id'] ?? '';
        $userName = $user['user_name'] ?? '';
        
        $info['box']  = $email;
        $info['name'] = $userName;
        $info['id']   = $userId;
        
        return $info;
    }
    
    private function token($userId)
    {
        $date = new \DateTime(date('Y-m-d H:i:s'));
        
        $arrToken['exp'] = $date->format('Y-m-d H:i:s');
        $arrToken['id']  = $userId;
        
        keyShuffle($arrToken);
        
        $jsonToken = json_encode($arrToken);
        
        return krypt('encrypt', $jsonToken);
    }
}