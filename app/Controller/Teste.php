<?php

namespace App\Controller;

final class Teste
{

    private $container;

    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
    }

    public function cadastrar(\Slim\Http\Request $request, \Slim\Http\Response $response): \Slim\Http\Response
    {

        //exemplo de validacao
        //este exemplo se encontra na controller apenas a carater de testes
        $a = [
            'email' => 'renan@gmail.com'
        ];
        
        $validation = new \Cake\Validation\Validator();
        $validation->email('email', true, 'nao e valido')
                   ->allowEmpty('email');
        
        $erros = $validation->errors($a);
        $errosdescricao = empty($erros) ? null : $erros;
         
        return $response->withJson([
            'mensagem' => $errosdescricao ?? 'Ok'
        ]);
        
    }

}
