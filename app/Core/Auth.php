<?php
namespace Core;

class Auth {
    public static function check(): bool { return (bool) Session::get('user'); }
    public static function user() { return Session::get('user'); }
    public static function id(): ?int { return self::user()['id'] ?? null; }
    public static function role(): ?string { return self::user()['role'] ?? null; }
    public static function is(string $role): bool { return self::role() === $role; }
    public static function can(array $roles): bool { return in_array(self::role(), $roles, true); }
}
