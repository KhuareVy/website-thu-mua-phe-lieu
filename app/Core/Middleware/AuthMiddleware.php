<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Middleware\MiddlewareInterface;
use App\Core\Middleware\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $path = $request->getUri();
        $publicPrefixes = ['/login', '/register', '/forgot-password'];
        foreach ($publicPrefixes as $prefix) {
            if (strpos($path, $prefix) === 0) {
                return $handler->handle($request);
            }
        }

        if (!Session::getInstance()->isLoggedIn()) {
            $response = new Response();
            if ($request->isAjax()) {
                return $response->json(['error' => 'Unauthorized'], 401);
            }
            return $response->redirect('/login');
        }

        return $handler->handle($request);
    }
}
