<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class RoleMiddleware implements MiddlewareInterface
{
    private array $allowedRoles;


    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $session = Session::getInstance();
        $user = $session->getUser();
        $userRole = $user['role'] ?? null;

        if (!$userRole) {
            $response = new Response();
            if ($request->isAjax()) {
                return $response->json(['error' => 'No role assigned'], 403);
            }
            return $response->redirect('/403');
        }

        if (!in_array($userRole, $this->allowedRoles, true)) {
            $response = new Response();
            if ($request->isAjax()) {
                return $response->json(['error' => 'Forbidden'], 403);
            }
            return $response->redirect('/403');
        }

        return $handler->handle($request);
    }
}
