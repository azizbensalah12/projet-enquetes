<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\User;

class UserController extends Controller {
    private User $model;

    public function __construct() { $this->model = new User(); }

    private function guard(): void {
        if (!Auth::can(['admin'])) { http_response_code(403); echo "Forbidden"; exit; }
    }

    public function index(): void {
        $this->guard();
        $users = $this->model->all();
        $this->view('users/index', compact('users')); // layout 'back' par défaut
    }

    public function create(): void {
        $this->guard();
        $this->view('users/create');
    }

    public function store(): void {
        $this->guard();

        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass  = (string)($_POST['password'] ?? '');
        $role  = $_POST['role'] ?? 'client';

        $errors = [];
        if ($name === '' || mb_strlen($name) > 100) $errors[] = "Nom requis (≤ 100).";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) $errors[] = "Email invalide (≤ 150).";
        if (!in_array($role, ['admin','agent','client'], true)) $errors[] = "Rôle invalide.";
        if (mb_strlen($pass) < 6) $errors[] = "Mot de passe ≥ 6 caractères.";

        if ($errors) {
            $this->view('users/create', compact('errors','name','email','role'));
            return;
        }

        $this->model->create([
            'name'     => $name,
            'email'    => $email,
            'password' => $pass,
            'role'     => $role
        ]);

        $this->redirect('/admin/users');
    }

    public function edit($id): void {
        $this->guard();
        $user = $this->model->find((int)$id);
        if (!$user) { http_response_code(404); echo "Utilisateur introuvable"; return; }
        $this->view('users/edit', compact('user'));
    }

    public function update($id): void {
        $this->guard();

        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $role  = $_POST['role'] ?? 'client';
        $pass  = (string)($_POST['password'] ?? ''); // facultatif

        $errors = [];
        if ($name === '' || mb_strlen($name) > 100) $errors[] = "Nom requis (≤ 100).";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) $errors[] = "Email invalide (≤ 150).";
        if (!in_array($role, ['admin','agent','client'], true)) $errors[] = "Rôle invalide.";
        if ($pass !== '' && mb_strlen($pass) < 6) $errors[] = "Nouveau mot de passe ≥ 6 caractères.";

        if ($errors) {
            $user = ['id'=>(int)$id,'name'=>$name,'email'=>$email,'role'=>$role];
            $this->view('users/edit', compact('errors','user'));
            return;
        }

        $this->model->update((int)$id, [
            'name'     => $name,
            'email'    => $email,
            'role'     => $role,
            'password' => $pass, // vide => non modifié (géré par le modèle)
        ]);

        $this->redirect('/admin/users');
    }

    public function destroy($id): void {
        $this->guard();
        $this->model->delete((int)$id);
        $this->redirect('/admin/users');
    }
}
