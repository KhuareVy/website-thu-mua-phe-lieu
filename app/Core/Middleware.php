<?php
namespace App\Core;


abstract class Middleware
{
    /**
     * Xử lý middleware.
     * @param mixed $request
     * @param callable $next
     * @return mixed
     *
     * Để dừng chuỗi middleware, trả về response hoặc throw exception.
     * Để tiếp tục, gọi return $next($request);
     *
     * Ví dụ override:
     * public function handle($request, $next) {
     *     if (!isset($_SESSION['user_id'])) {
     *         // Dừng chuỗi middleware, trả về response
     *         header('Location: /login');
     *         exit;
     *     }
     *     return $next($request);
     * }
     */
    public function handle($request, $next)
    {
        // Mặc định chỉ gọi tiếp middleware tiếp theo
        return $next($request);
    }
}
