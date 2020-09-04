<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 08/07/2017
 * Time: 16:21
 */
$json = [
    'name_msg' => 'Senha',
    //string com o nome do campo que será apresentado para interação com o usuário
    
    'validate' => 'password',
    //string que informa os parâmetros para validação do campo, recebe os seguintes valores:
    /*
    auto_increment: valida campo com numeração automática;
    date_automatic: valida um campo onde a data é informada automaticamente pelo sistema, padrão date('Y-m-d H:i:s');
    fk: valida um campo do tipo foreng key;
    string: valida um campo que contem apenas uma cadeia de caracteres;
    integer: valida campos que armazenam números inteiros;
    float: valida campos que armazenam números decimais;
    flag: os flags são campos especiais que utilizam o campo nome para gerar uma string sem acentos ou caracteres especiais;
    cpf: valida campos que armazenam CPFs;
    email: valida campos que armazenam Emails;
    token: valida campos que armazenam Tokens;
    login: valida campos que armazenam Login;
    password: valida campos que armazenam Passwords;
    */
    
    'validate_ref' => '',
    // string que informa a qual campo a validação faz referência, a exemplo os campos de validação do tipo "flag" ou "token", ele são gerados automaticamente pelos sistema com base nas funções "flag()" e "token()" respectivamente;
    
    'insert' => true,
    //valor booleano que informa se o campo estará disponível em páginas de inserção de registro;
    
    'update' => true,
    // valor booleano que informa se o campo estará disponível em páginas de alteração de registro;
    
    'comment' => 'Senha do usuário'
    // Armazena o comentário do campo .
];

echo json_encode($json);