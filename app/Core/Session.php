<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Session management with security features
 */
class Session
{
    private static ?Session $instance = null;
    private bool $started = false;

    private function __construct()
    {
        $this->start();
    }

    public static function getInstance(): Session
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Start session with security settings
     */
    public function start(): void
    {
        if ($this->started || session_status() === PHP_SESSION_ACTIVE) {
            $this->started = true;
            return;
        }
        // Security settings
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_secure', '1'); // Set to 0 for HTTP
        ini_set('session.cookie_samesite', 'Strict');
        session_start();
        $this->started = true;
        // Regenerate session ID periodically for security
        if (!$this->has('_session_started')) {
            session_regenerate_id(true);
            $this->set('_session_started', time());
        }
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function clear(): void
    {
        $_SESSION = [];
    }

    public function destroy(): void
    {
        if ($this->started) {
            session_destroy();
            $this->started = false;
        }
    }

    public function flash(string $key, $value = null)
    {
        if ($value !== null) {
            $this->set("_flash_{$key}", $value);
            return;
        }
        $flashKey = "_flash_{$key}";
        $value = $this->get($flashKey);
        $this->remove($flashKey);
        return $value;
    }

    public function getCsrfToken(): string
    {
        if (!$this->has('_csrf_token')) {
            $this->set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return $this->get('_csrf_token');
    }

    public function verifyCsrfToken(string $token): bool
    {
        return hash_equals($this->getCsrfToken(), $token);
    }

    public function login(array $user): void
    {
        $this->set('user_id', $user['id']);
        $this->set('user_data', $user);
        session_regenerate_id(true);
    }

    public function logout(): void
    {
        $this->remove('user_id');
        $this->remove('user_data');
        session_regenerate_id(true);
    }

    public function isLoggedIn(): bool
    {
        return $this->has('user_id');
    }

    public function getUser(): ?array
    {
        return $this->get('user_data');
    }

    public function getUserId(): ?int
    {
        return $this->get('user_id');
    }
}
