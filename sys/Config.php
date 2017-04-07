<?php

namespace App;

final class Config
{
    
    /**
     * Pega no arquivo config.json o tipo de configuracao desejada. Caso o tipo
     * nao seja encontrado o metodo ira retornar null
     * @param string $key
     * @return \stdClass | boolean
     */
    public function getConfig($key)
    {
        
        return json_decode(file_get_contents('config/config.json'))->$key ?? null;
        
    }

}
