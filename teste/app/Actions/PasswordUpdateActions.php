<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 24/06/2018
 * Time: 18:26
 */

namespace App\Actions;

use App\Entities\Auth\PasswordRecover\PasswordRecover;
use Pandora\Contracts\Connection\iConn;
use Pandora\Contracts\Database\iDataManager;
use Pandora\Contracts\Validation\iValidation;
use Slim\Container;

class PasswordUpdateActions
{
    /**
     * @var \Pandora\Contracts\Connection\iConn
     */
    private $conn;
    
    /**
     * @var \Pandora\Contracts\Database\iDataManager
     */
    private $dm;
    
    /**
     * @var \Pandora\Contracts\Validation\iValidation
     */
    private $validation;
    
    /**
     * @var \App\Entities\Auth\PasswordRecover\PasswordRecover
     */
    private $passwordRecover;
    
    /**
     * PasswordRecoverActions constructor
     *
     * @param \Slim\Container $container
     */
    public function __construct(Container $container)
    {
        $this->setValidation($container['validation']);
        $this->setDm($container['dmPasswordRecover']);
        $this->setConn($container['conn']);
        $this->setPasswordRecover($this->dm->getObject());
    }
    
    /**
     * @param array  $parameters
     * @param string $parameter
     * @param string $valueDefault
     * @param string $type
     *
     * @return mixed|string
     */
    public function extractRequest(array $parameters, string $parameter, string $valueDefault, string $type = 'normal')
    {
        if (isset($_REQUEST[$parameter])) {
            $value = !empty($_REQUEST[$parameter]) ? $_REQUEST[$parameter] : $valueDefault;
        } else {
            $value = $parameters[$parameter] ?? $valueDefault;
        }
        
        switch ($type) {
            case 'flag':
                $value = flag($value);
                break;
            case 'token_user':
                $value = token_user(str_replace('ipt_', '', $parameter), $value);
                break;
            case 'password':
                $value = password($value);
                break;
            case 'date_automatic':
                $value = date('Y-m-d H:i:s');
                break;
        }
        
        return $value;
    }
    
    /**
     * @param array $parameters
     *
     * @return string
     */
    public function update(array $parameters = [])
    {
        $id = $this->extractRequest($parameters, 'ipt_id', '');
        
        $date_update = date('Y-m-d h:i:s');
        $condition   = 'B';
        
        $passwordRecover = $this->getPasswordRecover();
        
        $passwordRecover->setId($id);
        $passwordRecover->setDate_update($date_update);
        $passwordRecover->setCondition($condition);
        
        $dm = $this->getDm();
        
        $dm->setObject($passwordRecover);
        
        $op = $dm->update();
        
        $msg = $op['message'];
        $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';
        
        return $msg;
    }
    
    /**
     * @param \Pandora\Contracts\Connection\iConn $conn
     *
     * @return $this
     */
    private function setConn(iConn $conn)
    {
        $this->conn = $conn;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    private function getDm()
    {
        return $this->dm;
    }
    
    /**
     * @param \Pandora\Contracts\Database\iDataManager $dm
     *
     * @return $this
     */
    private function setDm(iDataManager $dm)
    {
        $this->dm = $dm;
        
        return $this;
    }
    
    /**
     * @return \APP\Entities\Auth\PasswordRecover\passwordRecover
     */
    private function getPasswordRecover(): PasswordRecover
    {
        return $this->passwordRecover;
    }
    
    /**
     * @param $passwordRecover
     *
     * @return \App\Actions\PasswordUpdateActions
     */
    private function setPasswordRecover($passwordRecover): PasswordUpdateActions
    {
        $this->passwordRecover = $passwordRecover;
        
        return $this;
    }
    
    /**
     * @param \Pandora\Contracts\Validation\iValidation $validation
     *
     * @return $this
     */
    private function setValidation(iValidation $validation)
    {
        $this->validation = $validation;
        
        return $this;
    }
    
}