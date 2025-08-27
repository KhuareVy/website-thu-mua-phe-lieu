<?php
namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}
