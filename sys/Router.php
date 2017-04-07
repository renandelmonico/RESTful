<?php

namespace App;

final class Router
{
    
    /**
     * Rotas do sistema
     * @param \Slim\App $slim
     */
    public function setRoutes(\Slim\App &$slim)
    {
        
        $slim->get('/teste/cadastrar/{id}', '\\App\\Controller\\Teste:cadastrar');
        $slim->get('/admin/cadastrar/{id}', '\\App\\Controller\\Teste:cadastrar');
        $slim->get('/auth/login', '\\App\\Controller\\Auth:login');
        
    }

}
