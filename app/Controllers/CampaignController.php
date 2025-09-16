<?php
namespace App\Controllers;
use Core\Controller;
use Core\Auth;
use App\Models\Campaign;
use App\Models\Question;
use App\Models\User;

class CampaignController extends Controller {
    private Campaign $campaigns;
    private Question $questions;
    private User $users;
    public function __construct() { $this->campaigns=new Campaign(); $this->questions=new Question(); $this->users=new User(); }

    private function guard(): void { if (!Auth::can(['admin','agent'])) { http_response_code(403); echo "Forbidden"; exit; } }

    public function index(): void { $this->guard(); $campaigns=$this->campaigns->all(); $this->view('campaigns/index', compact('campaigns')); }
    public function create(): void { $this->guard(); $questions=$this->questions->all(); $this->view('campaigns/create', compact('questions')); }
    public function store(): void {
        $this->guard();
        $id=$this->campaigns->create([
            'title'=>trim($_POST['title']??''),
            'description'=>trim($_POST['description']??''),
            'status'=>$_POST['status']??'draft'
        ]);
        // Attach questions if provided
        if (!empty($_POST['question_ids'] ?? [])) {
            foreach ($_POST['question_ids'] as $qid) {
                $this->questions->attachToCampaign((int)$qid, $id);
            }
        }
        $this->redirect('/back/campaigns');
    }
    public function edit($id): void {
        $this->guard();
        $campaign=$this->campaigns->find((int)$id);
        $questions=$this->questions->all();
        $this->view('campaigns/edit', compact('campaign','questions'));
    }
    public function update($id): void {
        $this->guard();
        $this->campaigns->updateCampaign((int)$id, [
            'title'=>trim($_POST['title']??''),
            'description'=>trim($_POST['description']??''),
            'status'=>$_POST['status']??'draft'
        ]);
        $this->redirect('/back/campaigns');
    }
    public function destroy($id): void { $this->guard(); $this->campaigns->delete((int)$id); $this->redirect('/back/campaigns'); }

    public function assignToClient($id): void {
        $this->guard();
        $clientId = (int)($_POST['client_id'] ?? 0);
        $this->campaigns->assignToClient((int)$id, $clientId);
        $this->redirect('/back/campaigns');
    }
}
