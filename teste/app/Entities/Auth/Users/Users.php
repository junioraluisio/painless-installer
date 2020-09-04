<?php
/**
 * Created by Invoker.
 * Author: Aluisio Martins Junior <junior@mjpsolucoes.com.br>
 * Date: 22/07/2017
 * Time: 13:07
*/

namespace App\Entities\Auth\Users;


use Pandora\Contracts\Database\iDataObject;


class Users implements iDataObject
{
    /**
     * @var int $id ID do usuário. [max-length: 10,0]
     */
    private $id;

    /**
     * @var int $role_id ID do papel executado pelo usuário. [max-length: 10,0]
     */
    private $role_id;

    /**
     * @var string $name Nome do usuário. [max-length: 200]
     */
    private $name;

    /**
     * @var string $flag Flag do usuário. [max-length: 200]
     */
    private $flag;

    /**
     * @var string $email Email do usuário. [max-length: 200]
     */
    private $email;

    /**
     * @var string $login Login do usuário. [max-length: 200]
     */
    private $login;

    /**
     * @var string $password Senha do usuário. [max-length: 200]
     */
    private $password;

    /**
     * @var string $token Token JWT do usuário. [max-length: 65535]
     */
    private $token;

    /**
     * @var string $condition . [max-length: 1]
     */
    private $condition;

    /**
     * @var string
     */
    private $prefix = 'user_';

    /**
     * @var string
     */
    private $table  = 'auth_users';

    /**
     * @param int $id ID do usuário. [max-length: 10,0]
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
     * @param int $role_id ID do papel executado pelo usuário. [max-length: 10,0]
     *
     * @return $this
     */
    public function setRole_id($role_id)
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * return int
     */
    public function getRole_id()
    {
        return $this->role_id;
    }

    /**
     * @param string $name Nome do usuário. [max-length: 200]
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $flag Flag do usuário. [max-length: 200]
     *
     * @return $this
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * return string
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param string $email Email do usuário. [max-length: 200]
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
     * @param string $login Login do usuário. [max-length: 200]
     *
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $password Senha do usuário. [max-length: 200]
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $token Token JWT do usuário. [max-length: 65535]
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
     * @param string $condition . [max-length: 1]
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