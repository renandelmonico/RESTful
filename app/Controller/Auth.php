<?php

namespace App\Controller;

final class Auth
{

    private $container;

    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
    }

    /**
     * Realiza o login no sistema para acessar os metodos de administrador
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return json
     */
    public function login(\Slim\Http\Request $request, \Slim\Http\Response $response): \Slim\Http\Response
    {

        $Server = filter_input_array(INPUT_SERVER);

        $tokenId = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $serverName = filter_input(INPUT_SERVER, 'SERVER_ADDR') ?? filter_input(INPUT_SERVER, 'SERVER_NAME');
        $userData = [
            'user' => 'renan',
            'pass' => 'teste'
        ];

        $data = [
            'iat' => $issuedAt, // Issued at: time when the token was generated
            'jti' => $tokenId, // Json Token Id: an unique identifier for the token
            'iss' => $serverName, // Issuer
            'nbf' => (time() - 7200), // Not before (02:00:00)
            'exp' => strtotime("tomorrow"), // Expire (Amanha 0:00:00)
            'data' => $userData
        ];
        
        $token = \Firebase\JWT\JWT::encode($data, $this->container->get('config')->getConfig('auth')->key);
        
        return $response->withJson([
            'token' => $token
        ]);
        
    }

}
