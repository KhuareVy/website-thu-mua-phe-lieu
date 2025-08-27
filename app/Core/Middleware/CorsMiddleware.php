<?php
namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;

class CorsMiddleware implements MiddlewareInterface
{
    private array $allowedOrigins;
    private array $allowedMethods;
    private array $allowedHeaders;

    public function __construct(
        array $allowedOrigins = ['*'],
        array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        array $allowedHeaders = ['Content-Type', 'Authorization']
    ) {
        $this->allowedOrigins = $allowedOrigins;
        $this->allowedMethods = $allowedMethods;
        $this->allowedHeaders = $allowedHeaders;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $response = $handler->handle($request);
        $response->setHeader('Access-Control-Allow-Origin', implode(',', $this->allowedOrigins))
                 ->setHeader('Access-Control-Allow-Methods', implode(',', $this->allowedMethods))
                 ->setHeader('Access-Control-Allow-Headers', implode(',', $this->allowedHeaders));
        if ($request->getMethod() === 'OPTIONS') {
            $response->setStatusCode(200);
        }
        return $response;
    }
}
