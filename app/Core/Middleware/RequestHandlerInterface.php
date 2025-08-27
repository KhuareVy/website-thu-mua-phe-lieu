<?php
namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}
