<?php

namespace App\Middleware;
 
use \Psr\Log\LoggerInterface;
use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;
 
final class Log
{
    private $logger;
 
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Metodo que registra o log
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param \System\callable  $next
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $requisicao = $next($request, $response);

        $logString = '(' . $request->getUri()->getPath() . ') - ' . $requisicao->getBody();
        
        $status = $requisicao->getStatusCode();
        
        if ($status >= 200 && $status <= 299) {
            $level = 'info';
        } elseif ($status >= 400 && $status <= 499) {
            $level = 'warning';
        } elseif ($status >= 500 && $status <= 599) {
            $level = 'alert';
        }

        $this->logger->$level('#' . $status . ': ' . $logString);

        return $requisicao;
    }
}