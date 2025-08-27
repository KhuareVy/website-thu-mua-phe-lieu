<?php
namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class RateLimitMiddleware implements MiddlewareInterface
{
    private int $maxRequests;
    private int $timeWindow;

    public function __construct(int $maxRequests = 100, int $timeWindow = 3600)
    {
        $this->maxRequests = $maxRequests;
        $this->timeWindow = $timeWindow;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $ip = $request->getServerParam('REMOTE_ADDR');
        $key = "rate_limit_{$ip}";
        $session = Session::getInstance();
        $requests = $session->get($key, []);
        $now = time();
        $requests = array_filter($requests, function($timestamp) use ($now) {
            return ($now - $timestamp) < $this->timeWindow;
        });
        if (count($requests) >= $this->maxRequests) {
            $response = new Response();
            return $response->json(['error' => 'Rate limit exceeded'], 429);
        }
        $requests[] = $now;
        $session->set($key, $requests);
        return $handler->handle($request);
    }
}
