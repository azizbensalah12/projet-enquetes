<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\DB;
use PDO;
use App\Models\Campaign;
use App\Models\Question;
use App\Models\User;

class CampaignController extends Controller {
    private Campaign $campaigns;
    private Question $questions;
    private User $users;

    public function __construct() {
        $this->campaigns = new Campaign();
        $this->questions = new Question();
        $this->users     = new User();
    }

    private function guard(): void {
        if (!Auth::can(['admin','agent'])) { http_response_code(403); echo "Forbidden"; exit; }
    }

    /** ===== CRUD basique déjà présent ===== */
    public function index(): void {
        $this->guard();
        $campaigns = $this->campaigns->all();
        $this->view('campaigns/index', compact('campaigns'));
    }

    public function create(): void {
        $this->guard();
        $questions = $this->questions->all();
        $this->view('campaigns/create', compact('questions'));
    }

    public function store(): void {
        $this->guard();
        $id = $this->campaigns->create([
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status'      => $_POST['status'] ?? 'draft'
        ]);

        // Attacher des questions si fournies
        if (!empty($_POST['question_ids'] ?? [])) {
            foreach ((array)$_POST['question_ids'] as $qid) {
                $this->questions->attachToCampaign((int)$qid, $id);
            }
        }
        $this->redirect('/back/campaigns');
    }

    public function edit($id): void {
        $this->guard();
        $campaign  = $this->campaigns->find((int)$id);
        $questions = $this->questions->all();
        $this->view('campaigns/edit', compact('campaign','questions'));
    }

    public function update($id): void {
        $this->guard();
        $this->campaigns->updateCampaign((int)$id, [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status'      => $_POST['status'] ?? 'draft'
        ]);
        $this->redirect('/back/campaigns');
    }

    public function destroy($id): void {
        $this->guard();
        $this->campaigns->delete((int)$id);
        $this->redirect('/back/campaigns');
    }

    /** ====== METIERS AVANCÉS ====== */

    /** Formulaire d’assignation (liste clients cochables) */
    public function assignForm($id): void {
        $this->guard();

        $cid = (int)$id;
        $pdo = DB::conn();

        $c = $pdo->prepare("SELECT id, title, status FROM campaigns WHERE id=:id");
        $c->execute(['id'=>$cid]);
        $campaign = $c->fetch(PDO::FETCH_ASSOC);
        if (!$campaign) { http_response_code(404); echo "Campagne introuvable"; return; }

        $clients = $pdo->query("SELECT id, name, email FROM users WHERE role='client' ORDER BY name")
                       ->fetchAll(PDO::FETCH_ASSOC);

        $as = $pdo->prepare("SELECT client_id FROM survey_assignments WHERE campaign_id=:id");
        $as->execute(['id'=>$cid]);
        $assignedIds = array_map('intval', array_column($as->fetchAll(PDO::FETCH_ASSOC), 'client_id'));

        $this->view('campaigns/assign', compact('campaign','clients','assignedIds'));
    }

    /**
     * Action POST d’assignation : on remplace toutes les assignations de la campagne
     * par la liste envoyée (client_ids[]).
     */
    public function assignToClient($id): void {
        $this->guard();

        $cid = (int)$id;
        $ids = array_map('intval', $_POST['client_ids'] ?? []); // tableau de clients cochés

        $pdo = DB::conn();
        $pdo->beginTransaction();
        try {
            $del = $pdo->prepare("DELETE FROM survey_assignments WHERE campaign_id=:cid");
            $del->execute(['cid'=>$cid]);

            if (!empty($ids)) {
                $ins = $pdo->prepare("
                    INSERT INTO survey_assignments (campaign_id, client_id)
                    VALUES (:cid, :uid)
                ");
                foreach ($ids as $uid) {
                    if ($uid > 0) {
                        $ins->execute(['cid'=>$cid, 'uid'=>$uid]);
                    }
                }
            }

            $pdo->commit();
            $this->redirect('/back/campaigns/'.$cid.'/assign');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo "Erreur assignation : " . $e->getMessage();
        }
    }

    /** Statistiques par campagne */
    public function stats($id): void {
        $this->guard();

        $cid = (int)$id;
        $pdo = DB::conn();

        $c = $pdo->prepare("SELECT id, title, description, status FROM campaigns WHERE id=:id");
        $c->execute(['id'=>$cid]);
        $campaign = $c->fetch(PDO::FETCH_ASSOC);
        if (!$campaign) { http_response_code(404); echo "Campagne introuvable"; return; }

        // Répondants uniques
        $st = $pdo->prepare("SELECT COUNT(DISTINCT client_id) FROM responses WHERE campaign_id=:id");
        $st->execute(['id'=>$cid]);
        $respondents = (int)$st->fetchColumn();

        // Stats numériques (moyenne)
        $num = $pdo->prepare("
            SELECT q.id, q.label, AVG(CAST(r.answer_text AS DECIMAL(10,2))) AS avg_val, COUNT(*) AS cnt
            FROM responses r
            JOIN questions q ON q.id=r.question_id
            WHERE r.campaign_id=:id AND q.type='number'
            GROUP BY q.id, q.label
            ORDER BY q.id
        ");
        $num->execute(['id'=>$cid]);
        $statsNumber = $num->fetchAll(PDO::FETCH_ASSOC);

        // Stats select (répartition)
        $sel = $pdo->prepare("
            SELECT q.id, q.label, r.answer_text AS option_val, COUNT(*) AS nb
            FROM responses r
            JOIN questions q ON q.id=r.question_id
            WHERE r.campaign_id=:id AND q.type='select'
            GROUP BY q.id, q.label, r.answer_text
            ORDER BY q.id
        ");
        $sel->execute(['id'=>$cid]);
        $rows = $sel->fetchAll(PDO::FETCH_ASSOC);

        $statsSelect = [];
        foreach ($rows as $row) {
            $qid = (int)$row['id'];
            if (!isset($statsSelect[$qid])) {
                $statsSelect[$qid] = ['label'=>$row['label'], 'options'=>[]];
            }
            $statsSelect[$qid]['options'][] = ['value'=>$row['option_val'], 'nb'=>(int)$row['nb']];
        }

        $this->view('campaigns/stats', compact('campaign','respondents','statsNumber','statsSelect'));
    }

    /** Export CSV des réponses d’une campagne */
    public function exportCsv($id): void {
        $this->guard();

        $cid = (int)$id;
        $pdo = DB::conn();

        $c = $pdo->prepare("SELECT title FROM campaigns WHERE id=:id");
        $c->execute(['id'=>$cid]);
        $title = $c->fetchColumn();
        if (!$title) { http_response_code(404); echo "Campagne introuvable"; return; }

        $st = $pdo->prepare("
            SELECT u.name AS client, u.email, q.label AS question, r.answer_text AS reponse, r.created_at
            FROM responses r
            JOIN users u ON u.id=r.client_id
            JOIN questions q ON q.id=r.question_id
            WHERE r.campaign_id=:id
            ORDER BY u.name, q.id
        ");
        $st->execute(['id'=>$cid]);

        $filename = 'responses_' . preg_replace('/[^a-z0-9]+/i','_', $title) . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Client','Email','Question','Réponse','Date'], ';');

        while ($row = $st->fetch(PDO::FETCH_NUM)) {
            fputcsv($out, $row, ';');
        }
        fclose($out);
        exit;
    }
}
