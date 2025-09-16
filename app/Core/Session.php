<?php
namespace Core;

class Session {
    public static function start(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'] ?? '/', $p['domain'] ?? '',
                $p['secure'] ?? false, $p['httponly'] ?? true
            );
        }

        @session_destroy();
    }

    public static function regenerate(): void {
        self::start();
        @session_regenerate_id(true);
    }
}
