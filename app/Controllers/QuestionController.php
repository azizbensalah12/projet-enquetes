<?php
namespace App\Controllers;
use Core\Controller;
use Core\Auth;
use App\Models\Question;

class QuestionController extends Controller {
    private Question $model;
    public function __construct(){ $this->model = new Question(); }

    private function guard(): void {
        if (!Auth::can(['admin','agent'])) { http_response_code(403); echo "Forbidden"; exit; }
    }

    public function index(): void {
        $this->guard();
        $questions = $this->model->all();
        $this->view('questions/index', compact('questions'), 'back');
    }

    public function create(): void {
        $this->guard();
        $this->view('questions/create', [], 'back');
    }

    public function store(): void {
        $this->guard();

        $label = trim($_POST['label'] ?? '');
        $type  = $_POST['type'] ?? 'text';
        $opts  = $_POST['options_json'] ?? null;

        $errors = [];
        if ($label === '' || mb_strlen($label) > 255) $errors[] = "Libellé requis (≤ 255).";
        if (!in_array($type, ['text','number','select'], true)) $errors[] = "Type invalide.";

        if ($type === 'select') {
            if ($opts === null || trim((string)$opts) === '') {
                $errors[] = "Pour le type 'select', les options (JSON) sont requises. Ex: [\"Oui\",\"Non\"]";
            } else {
                json_decode($opts, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $errors[] = "Options JSON invalides. Exemple valide : [\"Excellent\",\"Bien\",\"Moyen\",\"Faible\"]";
                }
            }
        } else {
            // très important : éviter de stocker '' => on met NULL
            $opts = null;
        }

        if ($errors) {
            $this->view('questions/create', compact('errors','label','type','opts'), 'back');
            return;
        }

        $this->model->create([
            'label' => $label,
            'type'  => $type,
            'options_json' => $opts
        ]);

        $this->redirect('/back/questions');
    }

    public function edit($id): void {
        $this->guard();
        $q = $this->model->find((int)$id);
        if (!$q) { http_response_code(404); echo "Question introuvable"; return; }
        $this->view('questions/edit', ['q'=>$q], 'back');
    }

    public function update($id): void {
        $this->guard();

        $label = trim($_POST['label'] ?? '');
        $type  = $_POST['type'] ?? 'text';
        $opts  = $_POST['options_json'] ?? null;

        $errors = [];
        if ($label === '' || mb_strlen($label) > 255) $errors[] = "Libellé requis (≤ 255).";
        if (!in_array($type, ['text','number','select'], true)) $errors[] = "Type invalide.";

        if ($type === 'select') {
            if ($opts === null || trim((string)$opts) === '') {
                $errors[] = "Pour le type 'select', les options (JSON) sont requises.";
            } else {
                json_decode($opts, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $errors[] = "Options JSON invalides. Exemple : [\"Oui\",\"Non\"]";
                }
            }
        } else {
            $opts = null;
        }

        if ($errors) {
            $q = ['id'=>(int)$id, 'label'=>$label, 'type'=>$type, 'options_json'=>$opts];
            $this->view('questions/edit', ['q'=>$q, 'errors'=>$errors], 'back');
            return;
        }

        $this->model->updateQuestion((int)$id, [
            'label' => $label,
            'type'  => $type,
            'options_json' => $opts
        ]);

        $this->redirect('/back/questions');
    }

    public function destroy($id): void {
        $this->guard();
        $this->model->delete((int)$id);
        $this->redirect('/back/questions');
    }
}
