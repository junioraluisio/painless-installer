<?php
/**
 * Created by Invoker.
 * Author: Aluisio Martins Junior <junior@mjpsolucoes.com.br>
 * Date: 15/06/2018
 * Time: 13:06
*/

namespace App\Actions;

use App\Entities\Auth\PasswordRecover\PasswordRecover;
use Pandora\Contracts\Actions\iActions;
use Pandora\Contracts\Connection\iConn;
use Pandora\Contracts\Database\iDataManager;
use Pandora\Contracts\Validation\iValidation;
use Slim\Container;

class PasswordRecoverActions implements iActions
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
     * @param array $parameters
     *
     * @return string
     */
    public function disable(array $parameters = [])
    {
        $id = $this->extractRequest($parameters, 'ipt_id', '');

        $passwordRecover = $this->getPasswordRecover();
        $passwordRecover->setId($id);

        $dm = $this->getDm();
        $dm->setObject($passwordRecover);

        $op = $dm->disableById();

        $msg = $op['message'];
        $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';

        return json_encode($msg);
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function enable(array $parameters = [])
    {
        $id = $this->extractRequest($parameters, 'ipt_id', '');

        $passwordRecover = $this->getPasswordRecover();
        $passwordRecover->setId($id);

        $dm = $this->getDm();
        $dm->setObject($passwordRecover);

        $op = $dm->enableById();

        $msg = $op['message'];
        $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';

        return json_encode($msg);
    }

    /**
     * @param array  $parameters
     * @param string $parameter
     * @param string $valueDefault
     * @param string $type
     *
     * @return mixed|string
     */
    public function extractRequest(array $parameters, string $parameter, string $valueDefault, string $type='normal')
    {
        if (isset($_REQUEST[$parameter])) {
            $value = !empty($_REQUEST[$parameter]) ? $_REQUEST[$parameter] : $valueDefault;
        } else {
            $value = $parameters[$parameter] ?? $valueDefault;
        }

        switch ($type){
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
     * @return mixed|string
     */
    public function insert(array $parameters = [])
    {
        $user_id      = $this->extractRequest($parameters, 'ipt_user_id', '');
        $email        = $this->extractRequest($parameters, 'ipt_email', '');
        $ip           = $this->extractRequest($parameters, 'ipt_ip', '');
        $device       = $this->extractRequest($parameters, 'ipt_device', '');
        $date_request = date('Y-m-d H:i:s');
        $token        = $this->extractRequest($parameters, 'ipt_token', '');

        $validation = $this->getValidation();

        $check = [];

        // Validação do campo user_id
        array_push($check, $validation->isNotEmpty($user_id, 'ID do usuário'));

        // Validação do campo email
        array_push($check, $validation->isNotEmpty($email, 'Email'));
        array_push($check, $validation->isEmail($email));

        // Validação do campo ip
        array_push($check, $validation->isNotEmpty($ip, 'IP do usuário'));
        array_push($check, $validation->isIp($ip));

        // Validação do campo device
        array_push($check, $validation->isNotEmpty($device, 'Dispositivo'));

        // Validação do campo date_request
        array_push($check, $validation->isNotEmpty($date_request, 'Data da solicitação'));

        // Validação do campo token
        array_push($check, $validation->isNotEmpty($token, 'Token'));

        $error = 0;

        $msg = [];

        foreach ($check as $item) {
            $error += ($item['response'] === false) ? 1 : 0;

            if (!empty($item['message'])) {
                $msg[] = $item['message'];
            }

        }

        if ($error < 1) {
            $passwordRecover = $this->getPasswordRecover();

            $passwordRecover->setUser_id($user_id);
            $passwordRecover->setEmail($email);
            $passwordRecover->setIp($ip);
            $passwordRecover->setDevice($device);
            $passwordRecover->setDate_request($date_request);
            $passwordRecover->setToken($token);

            $dm = $this->getDm();
            $dm->setObject($passwordRecover);

            $op = $dm->insert();

            $msg = $op['message'];
            $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';
        }

        return json_encode($msg);
    }

    /**
     * @param array $parameters
     *
     * @return mixed|string
     */
    public function update(array $parameters = [])
    {
        $error = 0;

        $msg = [];

        $id = $this->extractRequest($parameters, 'ipt_id', '');

        if(empty($id)) {
            $msg[] = 'Identificador inválido!';
        }

        $email       = $this->extractRequest($parameters, 'ipt_email', '');
        $ip          = $this->extractRequest($parameters, 'ipt_ip', '');
        $device      = $this->extractRequest($parameters, 'ipt_device', '');
        $date_update = $this->extractRequest($parameters, 'ipt_date_update', '');
        $condition   = $this->extractRequest($parameters, 'ipt_condition', 'A');

        $validation = $this->getValidation();

        $check = [];

        // Validação do campo email
        array_push($check, $validation->isNotEmpty($email, 'Email'));
        array_push($check, $validation->isEmail($email));

        // Validação do campo ip
        array_push($check, $validation->isNotEmpty($ip, 'IP do usuário'));
        array_push($check, $validation->isIp($ip));

        // Validação do campo device
        array_push($check, $validation->isNotEmpty($device, 'Dispositivo'));

        // Validação do campo condition
        array_push($check, $validation->isNotEmpty($condition, 'Situação'));


        foreach ($check as $item) {
            $error += ($item['response'] === false) ? 1 : 0;

            if (!empty($item['message'])) {
                $msg[] = $item['message'];
            }

        }

        if ($error < 1) {
            $passwordRecover = $this->getPasswordRecover();

            $passwordRecover->setId($id);
            $passwordRecover->setEmail($email);
            $passwordRecover->setIp($ip);
            $passwordRecover->setDevice($device);
            $passwordRecover->setDate_update($date_update);
            $passwordRecover->setCondition($condition);

            $dm = $this->getDm();

            $dm->setObject($passwordRecover);

            $op = $dm->update();

            $msg = $op['message'];
            $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';
        }

        return json_encode($msg);
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
     */private function getDm()
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
     * @param mixed $passwordRecover
     *
     * @return PasswordRecoverActions
     */
    private function setPasswordRecover($passwordRecover)
    {
        $this->passwordRecover = $passwordRecover;

        return $this;
    }

    /**
     * @return mixed
     */
    private function getValidation()
    {
        return $this->validation;
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