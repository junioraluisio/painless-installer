<?php
/**
 * Created by Invoker.
 * Author: Aluisio Martins Junior <junior@mjpsolucoes.com.br>
 * Date: 16/08/2017
 * Time: 19:08
 */

namespace App\Actions;

use App\Entities\Auth\Users\Users;
use Pandora\Contracts\Actions\iActions;
use Pandora\Contracts\Connection\iConn;
use Pandora\Contracts\Database\iDataManager;
use Pandora\Contracts\Validation\iValidation;

class UsersActions implements iActions
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
     * @var \App\Entities\Auth\Users\Users
     */
    private $users;
    
    /**
     * @var string
     */
    private $table = 'auth_users';
    
    public function __construct($container)
    {
        $this->setValidation($container['validation']);
        $this->setDm($container['dmUsers']);
        $this->setConn($container['conn']);
        $this->setUsers($this->dm->getObject());
    }
    
    /**
     *
     * @return string
     */
    public function insert()
    {
        $role_id  = $_REQUEST['ipt_role_id'] ?? '';
        $name     = $_REQUEST['ipt_name'] ?? '';
        $flag     = isset($_REQUEST['ipt_name']) ? flag($_REQUEST['ipt_name']) : '';
        $email    = $_REQUEST['ipt_email'] ?? '';
        $login    = $_REQUEST['ipt_login'] ?? '';
        $password = isset($_REQUEST['ipt_password']) ? password($_REQUEST['ipt_password']) : '';
        $token    = isset($_REQUEST['ipt_email']) ? token_user('email', $_REQUEST['ipt_email']) : '';
        
        $validation = $this->getValidation();
        $conn       = $this->getConn();
        
        $check = [];
        
        // Validação do campo role_id
        array_push($check, $validation->isNotEmpty($role_id, 'ID do papel'));
        
        // Validação do campo name
        array_push($check, $validation->isNotEmpty($name, 'Nome'));
        
        // Validação do campo email
        array_push($check, $validation->isNotEmpty($email, 'Email'));
        array_push($check, $validation->isEmail($email));
        array_push($check, $validation->isUnique($conn, $this->table, 'user_email', $email));
        
        // Validação do campo login
        array_push($check, $validation->isNotEmpty($login, 'Login'));
        array_push($check, $validation->isLogin($login));
        array_push($check, $validation->isUnique($conn, $this->table, 'user_login', $login));
        
        // Validação do campo password
        array_push($check, $validation->isNotEmpty($password, 'Senha'));
        array_push($check, $validation->isPassword($password));
        
        $error = 0;
        
        $msg = [];
        
        foreach ($check as $item) {
            $error += ($item['response'] === false) ? 1 : 0;
            
            if (!empty($item['message'])) {
                $msg[] = $item['message'];
            }
            
        }
        
        if ($error < 1) {
            $users = $this->getUsers();
            
            $users->setRole_id($role_id);
            $users->setName($name);
            $users->setFlag($flag);
            $users->setEmail($email);
            $users->setLogin($login);
            $users->setPassword($password);
            $users->setToken($token);
            
            $dm = $this->getDm();
            $dm->setObject($users);
            
            $op = $dm->insert();
            
            $msg = $op['message'];
            $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';
        }
        
        return json_encode($msg);
    }
    
    /**
     *
     * @return string
     */
    public function update()
    {
        $id       = $_REQUEST['ipt_id'] ?? '';
        $role_id  = $_REQUEST['ipt_role_id'] ?? '';
        $name     = $_REQUEST['ipt_name'] ?? '';
        $flag     = isset($_REQUEST['ipt_name']) ? flag($_REQUEST['ipt_name']) : '';
        $email    = $_REQUEST['ipt_email'] ?? '';
        $login    = $_REQUEST['ipt_login'] ?? '';
        $password = isset($_REQUEST['ipt_password']) ? password($_REQUEST['ipt_password']) : '';
        $token    = isset($_REQUEST['ipt_email']) ? token_user('email', $_REQUEST['ipt_email']) : '';
        
        $validation = $this->getValidation();
        $conn       = $this->getConn();
        
        $check = [];
        
        // Validação do campo role_id
        array_push($check, $validation->isNotEmpty($role_id, 'ID do papel'));
        
        // Validação do campo name
        array_push($check, $validation->isNotEmpty($name, 'Nome'));
        
        // Validação do campo email
        array_push($check, $validation->isNotEmpty($email, 'Email'));
        array_push($check, $validation->isEmail($email));
        array_push($check, $validation->isUnique($conn, $this->table, 'user_email', $email));
        
        // Validação do campo login
        array_push($check, $validation->isNotEmpty($login, 'Login'));
        array_push($check, $validation->isLogin($login));
        array_push($check, $validation->isUnique($conn, $this->table, 'user_login', $login));
        
        // Validação do campo password
        array_push($check, $validation->isNotEmpty($password, 'Senha'));
        array_push($check, $validation->isPassword($password));
        
        
        $error = 0;
        
        $msg = [];
        
        foreach ($check as $item) {
            $error += ($item['response'] === false) ? 1 : 0;
            
            if (!empty($item['message'])) {
                $msg[] = $item['message'];
            }
            
        }
        
        if ($error < 1) {
            $users = $this->getUsers();
            
            $users->setId($id);
            $users->setRole_id($role_id);
            $users->setName($name);
            $users->setFlag($flag);
            $users->setEmail($email);
            $users->setLogin($login);
            $users->setPassword($password);
            $users->setToken($token);
            
            $dm = $this->getDm();
            
            $dm->setObject($users);
            
            $op = $dm->update();
            
            $msg = $op['message'];
            $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';
        }
        
        return json_encode($msg);
    }
    
    /**
     *
     * @return string
     */
    public function disable()
    {
        $id = $_REQUEST['ipt_id'] ?? '';
        
        $users = $this->getUsers();
        $users->setId($id);
        
        $dm = $this->getDm();
        $dm->setObject($users);
        
        $op = $dm->disableById();
        
        $msg = $op['message'];
        $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';
        
        return json_encode($msg);
    }
    
    /**
     *
     * @return string
     */
    public function enable()
    {
        $id = $_REQUEST['ipt_id'] ?? '';
        
        $users = $this->getUsers();
        $users->setId($id);
        
        $dm = $this->getDm();
        $dm->setObject($users);
        
        $op = $dm->enableById();
        
        $msg = $op['message'];
        $msg .= !empty($op['error_info']) ? ' :: ' . $op['error_info'] : '';
        
        return json_encode($msg);
    }
    
    /**
     * @return mixed
     */
    private function getConn()
    {
        return $this->conn;
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
     * @return \APP\Entities\Auth\Users\users
     */
    private function getUsers(): Users
    {
        return $this->users;
    }
    
    /**
     * @param mixed $users
     *
     * @return UsersActions
     */
    private function setUsers($users)
    {
        $this->users = $users;
        
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