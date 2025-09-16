<?php
namespace Core;

use PDO, PDOException;
use Dotenv\Dotenv;

class DB {
    private static ?PDO $pdo = null;

    public static function conn(): PDO {
        if (self::$pdo === null) {
            // Chemin racine du projet (app/Core => remonter de 2 niveaux)
            $root = dirname(__DIR__, 2);
            if (file_exists($root.'/.env')) {
                Dotenv::createImmutable($root)->load();
            }

            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $db   = $_ENV['DB_DATABASE'] ?? 'web_survey';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';
            $port = (int)($_ENV['DB_PORT'] ?? 3306);
            $dsn  = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT            => 5,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                // PDO::ATTR_PERSISTENT => false, // (par défaut) éviter des connexions persistantes instables
            ]);
        }

        // petite vérification "keep-alive" : si la connexion a été coupée, on reconnecte
        try {
            self::$pdo->query('SELECT 1');
        } catch (PDOException $e) {
            if (stripos($e->getMessage(), 'server has gone away') !== false) {
                self::$pdo = null;
                return self::conn();
            }
            throw $e;
        }

        return self::$pdo;
    }
}
