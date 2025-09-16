<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\DB;
use PDO;

class SurveyController extends Controller
{
    /** Liste des campagnes assignées au client connecté */
    public function index(): void {
        if (!Auth::check()) { $this->redirect('/login'); }

        $uid = Auth::id();
        $pdo = DB::conn();

       
        $sql = "
            SELECT c.id, c.title, c.description, c.status, c.created_at
            FROM survey_assignments sa
            JOIN campaigns c ON c.id = sa.campaign_id
            WHERE sa.client_id = :uid AND c.status = 'published'
            ORDER BY c.created_at DESC
        ";
        $st = $pdo->prepare($sql);
        $st->execute(['uid' => $uid]);
        $campaigns = $st->fetchAll(PDO::FETCH_ASSOC);

        $this->view('campaigns/list_front', compact('campaigns'), 'front');
    }

    /** Formulaire d’une campagne */
    public function show($id): void {
        if (!Auth::check()) { $this->redirect('/login'); }

        $uid = Auth::id();
        $cid = (int)$id;
        $pdo = DB::conn();

        // sécurité : cette campagne est-elle assignée à ce client ?
        $ok = $pdo->prepare("SELECT 1 FROM survey_assignments WHERE client_id = :uid AND campaign_id = :cid");
        $ok->execute(['uid' => $uid, 'cid' => $cid]);
        if (!$ok->fetchColumn()) { http_response_code(403); echo "Non autorisé"; return; }

        $c = $pdo->prepare("SELECT id, title, description FROM campaigns WHERE id = :cid AND status='published'");
        $c->execute(['cid' => $cid]);
        $campaign = $c->fetch(PDO::FETCH_ASSOC);
        if (!$campaign) { http_response_code(404); echo "Campagne introuvable"; return; }

        // récupérer les questions liées
        $q = $pdo->prepare("
            SELECT q.id, q.label, q.type, q.options_json
            FROM campaign_questions cq
            JOIN questions q ON q.id = cq.question_id
            WHERE cq.campaign_id = :cid
            ORDER BY q.id ASC
        ");
        $q->execute(['cid' => $cid]);
        $questions = $q->fetchAll(PDO::FETCH_ASSOC);

        $this->view('campaigns/fill', compact('campaign','questions'), 'front');
    }

    /** Envoi des réponses */
    public function submit($id): void {
        if (!Auth::check()) { $this->redirect('/login'); }

        $uid = Auth::id();
        $cid = (int)$id;
        $pdo = DB::conn();

        // re-check assignation
        $ok = $pdo->prepare("SELECT 1 FROM survey_assignments WHERE client_id = :uid AND campaign_id = :cid");
        $ok->execute(['uid'=>$uid,'cid'=>$cid]);
        if (!$ok->fetchColumn()) { http_response_code(403); echo "Non autorisé"; return; }

        // questions de la campagne (pour valider les types)
        $q = $pdo->prepare("
            SELECT q.id, q.type, q.options_json
            FROM campaign_questions cq
            JOIN questions q ON q.id = cq.question_id
            WHERE cq.campaign_id = :cid
        ");
        $q->execute(['cid'=>$cid]);
        $questions = $q->fetchAll(PDO::FETCH_ASSOC);

        $ins = $pdo->prepare("
            INSERT INTO responses (campaign_id, question_id, client_id, answer_text)
            VALUES (:cid, :qid, :uid, :val)
        ");

        foreach ($questions as $question) {
            $qid = (int)$question['id'];
            $field = "q_$qid";
            $val = $_POST[$field] ?? null;

            if ($question['type'] === 'number') {
                if ($val === null || $val === '' || !is_numeric($val)) continue;
                $val = (string)$val;
            } elseif ($question['type'] === 'select') {
                $opts = $question['options_json'] ? json_decode($question['options_json'], true) : [];
                if (!in_array($val, (array)$opts, true)) continue;
                $val = (string)$val;
            } else { // text
                $val = trim((string)($val ?? ''));
            }

            $ins->execute([
                'cid' => $cid,
                'qid' => $qid,
                'uid' => $uid,
                'val' => $val,
            ]);
        }

        $this->redirect('/surveys');
    }
}
