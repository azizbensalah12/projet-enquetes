<?php
require __DIR__ . '/../vendor/autoload.php';

use Core\DB;

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = DB::conn();
    echo "DB: OK\n";

    // 1) normaliser la colonne (au cas où)
    $pdo->exec("ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL");

    // 2) normaliser l'email en base (trim + lower)
    $pdo->exec("UPDATE users SET email = LOWER(TRIM(email)) WHERE email LIKE 'admin@example.com%'");

    // 3) produire un hash propre et l'écrire
    $plain = 'admin123';
    $hash  = password_hash($plain, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("UPDATE users SET password=:p WHERE TRIM(LOWER(email))='admin@example.com'");
    $stmt->execute(['p' => $hash]);

    // 4) relire et diagnostiquer
    $row = $pdo->query("SELECT password FROM users WHERE TRIM(LOWER(email))='admin@example.com' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo "READBACK: user not found\n";
        exit;
    }

    $stored = $row['password'];
    echo "LEN=" . strlen($stored) . "\n";          // doit être ~60
    echo "START7=" . substr($stored, 0, 7) . "\n"; // doit être $2y$10$
    echo "VERIFY=" . (password_verify($plain, $stored) ? 'true' : 'false') . "\n";

    // 5) petit extra pour traquer d’éventuels caractères cachés
    echo "HEX=" . bin2hex($stored) . "\n";         // doit faire 120 hex chars

} catch (Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
