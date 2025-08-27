<?php
namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CsrfMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $request->getBodyParam('_csrf_token');
            if (!$token || !Session::getInstance()->verifyCsrfToken($token)) {
                $response = new Response();
                return $response->json(['error' => 'CSRF token mismatch'], 403);
            }
        }
        return $handler->handle($request);
    }
}
