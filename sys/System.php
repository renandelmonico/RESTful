<?php

namespace App;

final class System
{

    /**
     * Slim
     * @var \Slim\App
     */
    private $Slim;
    
    /**
     * Containers
     * @var \Slim\Container
     */
    private $Container;
    
    /**
     * Rotas do sistema
     * @var \App\Router
     */
    private $Router;
    
    /**
     * Interface de resposta
     * @var \System\Http\Response
     */
    private $Response;

    public function __construct($config)
    {

        $this->Router    = new \App\Router();
        $this->Slim      = new \Slim\App($config);
        $this->Response  = new \Slim\Http\Response();
        $this->Container = $this->Slim->getContainer();
        
    }

    /**
     * Rodar a aplicacao
     */
    public function run()
    {
        
        try {

            $this->Container['config']     = $this->getContainerConfig();
            $this->Container['connection'] = $this->getContainerConnection();
            $this->Container['logger']     = $this->getContainerLogger();

            $logger = $this->Container->get('logger');

            $this->Slim->add(new \App\Middleware\Log($logger, $this->Container));

            $this->Slim->add(new \App\Middleware\JwtAuthentication([
                'secret' => $this->Container->get('config')->getConfig('auth')->key,
                'path'   => '/admin',
                'secure' => true
            ]));

            $this->Router->setRoutes($this->Slim);

            $this->Slim->run();
        
        } catch (\Cake\Database\Exception\MissingConnectionException $e) {
            $this->respondInternalError('Houve um erro ao conectar ao banco de dados');
        } catch (\ErrorException $e) {
            $this->respondInternalError('Houve um erro interno: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->respondInternalError('Houve um erro no servidor');
        }
        
    }
    
    /**
     * Container do logger
     * @return \Monolog\Logger
     */
    private function getContainerLogger()
    {

        return function (\Slim\Container $c): \Monolog\Logger {

            $settings = $c->get('settings')['logger'];

            $logger   = new \Monolog\Logger($settings['name']);

            $handler  = new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG);

            $handler->setFormatter(new \Monolog\Formatter\LineFormatter(
                    "[%datetime%] %level_name% > %message% - %context% - %extra%\n"
            ));

            $logger->pushHandler($handler);
            $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
            $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
            $logger->pushProcessor(new \Monolog\Processor\MemoryPeakUsageProcessor());
            $logger->pushProcessor(new \Monolog\Processor\MemoryUsageProcessor());

            return $logger;
        };
        
    }
    
    /**
     * Classe de conexao com o banco de dados
     * @return \Cake\Database\Connection
     */
    private function getContainerConnection(): \Cake\Database\Connection
    {

        $conn = new \Cake\Database\Connection([
            'driver' => new \Cake\Database\Driver\Postgres([
                'host'     => $this->Container->get('config')->getConfig('database')->host,
                'port'     => $this->Container->get('config')->getConfig('database')->port,
                'username' => $this->Container->get('config')->getConfig('database')->username,
                'password' => $this->Container->get('config')->getConfig('database')->password,
                'database' => $this->Container->get('config')->getConfig('database')->database
            ])
        ]);
        
        $conn->connect();

        return $conn;
        
    }

    private function getContainerConfig(): \App\Config
    {
        return new \App\Config;
    }
    
    private function respondInternalError(string $message)
    {
        
        $this->Slim->respond($this->Response->withJSON([
            'success' => false,
            'message' => $message
        ], 500));
        
    }

}
