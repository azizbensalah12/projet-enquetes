<?php
namespace App\Models;
use Core\Model;
use PDO;

class Question extends Model {
    public function all(): array {
        return $this->db->query("SELECT * FROM questions ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare("
            INSERT INTO questions (label, type, options_json)
            VALUES (:label, :type, :opts)
        ");

        $opts = isset($d['options_json']) && trim((string)$d['options_json']) !== ''
            ? $d['options_json']
            : null;

        $stmt->execute([
            'label' => $d['label'],
            'type'  => $d['type'],
            'opts'  => $opts, // NULL si pas 'select'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function find(int $id): ?array {
        $st = $this->db->prepare("SELECT * FROM questions WHERE id=?");
        $st->execute([$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function updateQuestion(int $id, array $d): void {
        $stmt = $this->db->prepare("
            UPDATE questions
            SET label = :label, type = :type, options_json = :opts
            WHERE id = :id
        ");

        $opts = isset($d['options_json']) && trim((string)$d['options_json']) !== ''
            ? $d['options_json']
            : null;

        $stmt->execute([
            'label' => $d['label'],
            'type'  => $d['type'],
            'opts'  => $opts, // NULL si pas 'select'
            'id'    => $id,
        ]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM questions WHERE id=?")->execute([$id]);
    }

    public function attachToCampaign(int $questionId, int $campaignId): void {
        $this->db->prepare("
            INSERT IGNORE INTO campaign_questions (campaign_id, question_id)
            VALUES (?, ?)
        ")->execute([$campaignId, $questionId]);
    }
}
