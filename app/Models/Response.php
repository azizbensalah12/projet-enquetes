<?php
namespace App\Models;
use Core\Model;

class Response extends Model {
    public function create(int $campaignId, int $questionId, int $clientId, string $answer): void {
        $this->db->prepare("INSERT INTO responses (campaign_id, question_id, client_id, answer_text) VALUES (?, ?, ?, ?)")
                 ->execute([$campaignId, $questionId, $clientId, $answer]);
    }
    public function byCampaignAndClient(int $campaignId, int $clientId): array {
        $st = $this->db->prepare("SELECT r.*, q.label FROM responses r JOIN questions q ON q.id=r.question_id WHERE r.campaign_id=? AND r.client_id=?");
        $st->execute([$campaignId, $clientId]);
        return $st->fetchAll();
    }
}
