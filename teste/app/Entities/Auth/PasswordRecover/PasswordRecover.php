<?php
/**
 * Created by Invoker.
 * Author: Aluisio Martins Junior <junior@mjpsolucoes.com.br>
 * Date: 15/06/2018
 * Time: 13:06
*/

namespace App\Entities\Auth\PasswordRecover;


use Pandora\Contracts\Database\iDataObject;


class PasswordRecover implements iDataObject
{
    /**
     * @var int $id ID da recuperaÃ§Ã£o. [max-length: 10,0]
     */
    private $id;

    /**
     * @var int $user_id Id do usuÃ¡rio solicitante. [max-length: 10,0]
     */
    private $user_id;

    /**
     * @var string $email Email solicitante. [max-length: 150]
     */
    private $email;

    /**
     * @var string $ip IP do solicitante. [max-length: 100]
     */
    private $ip;

    /**
     * @var string $device Dispositivo do solicitante. [max-length: 100]
     */
    private $device;

    /**
     * @var string $date_request Data da solicitaÃ§Ã£o. [max-length: ,]
     */
    private $date_request;

    /**
     * @var string $date_update Data da atualizaÃ§Ã£o. [max-length: ,]
     */
    private $date_update;

    /**
     * @var string $token Token do link. [max-length: 150]
     */
    private $token;

    /**
     * @var string $condition SituaÃ§Ã£o do link. [max-length: 255]
     */
    private $condition;

    /**
     * @var string
     */
    private $prefix = 'recover_';

    /**
     * @var string
     */
    private $table  = 'auth_password_recover';

    /**
     * @param int $id ID da recuperaÃ§Ã£o. [max-length: 10,0]
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $user_id Id do usuÃ¡rio solicitante. [max-length: 10,0]
     *
     * @return $this
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * return int
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * @param string $email Email solicitante. [max-length: 150]
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $ip IP do solicitante. [max-length: 100]
     *
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $device Dispositivo do solicitante. [max-length: 100]
     *
     * @return $this
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param string $date_request Data da solicitaÃ§Ã£o. [max-length: ,]
     *
     * @return $this
     */
    public function setDate_request($date_request)
    {
        $this->date_request = $date_request;

        return $this;
    }

    /**
     * return string
     */
    public function getDate_request()
    {
        return $this->date_request;
    }

    /**
     * @param string $date_update Data da atualizaÃ§Ã£o. [max-length: ,]
     *
     * @return $this
     */
    public function setDate_update($date_update)
    {
        $this->date_update = $date_update;

        return $this;
    }

    /**
     * return string
     */
    public function getDate_update()
    {
        return $this->date_update;
    }

    /**
     * @param string $token Token do link. [max-length: 150]
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $condition SituaÃ§Ã£o do link. [max-length: 255]
     *
     * @return $this
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}