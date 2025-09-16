<?php
namespace App\Models;
use Core\Model;

class Campaign extends Model {
    public function all(): array {
        $sql = "SELECT c.*, COUNT(qc.question_id) AS questions_count
                FROM campaigns c
                LEFT JOIN campaign_questions qc ON qc.campaign_id=c.id
                GROUP BY c.id ORDER BY c.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }
    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM campaigns WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
    public function create(array $d): int {
        $stmt = $this->db->prepare("INSERT INTO campaigns (title, description, status) VALUES (?, ?, ?)");
        $stmt->execute([$d['title'], $d['description'], $d['status']]);
        return (int)$this->db->lastInsertId();
    }
    public function updateCampaign(int $id, array $d): void {
        $this->db->prepare("UPDATE campaigns SET title=?, description=?, status=? WHERE id=?")
                 ->execute([$d['title'], $d['description'], $d['status'], $id]);
    }
    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM campaigns WHERE id=?")->execute([$id]);
    }
    public function assignToClient(int $campaignId, int $clientId): void {
        $this->db->prepare("INSERT IGNORE INTO survey_assignments (campaign_id, client_id) VALUES (?, ?)")
                 ->execute([$campaignId, $clientId]);
    }
    public function questions(int $campaignId): array {
        $sql = "SELECT q.* FROM questions q
                JOIN campaign_questions cq ON cq.question_id=q.id
                WHERE cq.campaign_id=? ORDER BY q.id ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$campaignId]);
        return $st->fetchAll();
    }
}
