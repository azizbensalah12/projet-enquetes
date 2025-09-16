<?php
namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    /** Liste tous les utilisateurs (sans le hash) */
    public function all(): array {
        return $this->db->query("
            SELECT id, name, email, role, created_at
            FROM users
            ORDER BY id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Trouve un utilisateur par id (sans le hash) */
    public function find(int $id): ?array {
        $st = $this->db->prepare("
            SELECT id, name, email, role, created_at
            FROM users
            WHERE id = ?
        ");
        $st->execute([$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Trouve un utilisateur par email (avec le hash pour l'auth) */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("
            SELECT id, name, email, password, role
            FROM users
            WHERE TRIM(LOWER(email)) = TRIM(LOWER(:email))
            LIMIT 1
        ");
        $stmt->execute(['email' => trim($email)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Crée un utilisateur (hash le mot de passe) */
    public function create(array $data): int {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (:name, :email, :password, :role)
        ");
        $stmt->execute([
            'name'     => trim($data['name']),
            'email'    => trim($data['email']),
            'password' => $hash,
            'role'     => $data['role'] ?? 'client',
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Met à jour un utilisateur (hash si password fourni) */
    public function update(int $id, array $data): void {
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("
                UPDATE users
                SET name=:name, email=:email, role=:role, password=:password
                WHERE id=:id
            ");
            $stmt->execute([
                'name'     => trim($data['name']),
                'email'    => trim($data['email']),
                'role'     => $data['role'],
                'password' => $hash,
                'id'       => $id,
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE users
                SET name=:name, email=:email, role=:role
                WHERE id=:id
            ");
            $stmt->execute([
                'name'  => trim($data['name']),
                'email' => trim($data['email']),
                'role'  => $data['role'],
                'id'    => $id,
            ]);
        }
    }

    /** Supprime un utilisateur */
    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}
