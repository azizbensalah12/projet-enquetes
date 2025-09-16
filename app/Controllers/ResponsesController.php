<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\DB;
use PDO;

class ResponsesController extends Controller
{
    public function index(): void {
        if (!Auth::can(['admin','agent'])) { http_response_code(403); echo "Forbidden"; return; }

        $pdo = DB::conn();

        // Liste des campagnes pour le filtre
        $camps = $pdo->query("SELECT id, title FROM campaigns ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        $cid = isset($_GET['campaign_id']) ? (int)$_GET['campaign_id'] : null;

        $sql = "
          SELECT r.id, r.created_at, u.name AS client, u.email, q.label AS question, r.answer_text AS reponse, r.campaign_id
          FROM responses r
          JOIN users u ON u.id=r.client_id
          JOIN questions q ON q.id=r.question_id
        ";
        $params = [];
        if ($cid) {
            $sql .= " WHERE r.campaign_id = :cid";
            $params['cid'] = $cid;
        }
        $sql .= " ORDER BY r.created_at DESC, r.id DESC LIMIT 200";

        $st = $pdo->prepare($sql);
        $st->execute($params);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        $this->view('responses/index', [
            'campaigns' => $camps,
            'rows'      => $rows,
            'cid'       => $cid,
        ]);
    }
}
