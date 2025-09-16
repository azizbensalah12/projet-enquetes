<?php
namespace App\Controllers;

use Core\Controller;
use Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void {
        $this->view('auth/login', [], 'front');
    }

    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $pass  = (string)($_POST['password'] ?? '');

        if ($email === '' || $pass === '') {
            $this->view('auth/login', ['error' => 'Email et mot de passe requis.'], 'front');
            return;
        }

        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->view('auth/login', ['error' => 'Utilisateur introuvable (email).'], 'front');
            return;
        }
        if (!password_verify($pass, $user['password'])) {
            $this->view('auth/login', ['error' => 'Mot de passe invalide.'], 'front');
            return;
        }

        Session::set('user', [
            'id'    => (int)$user['id'],
            'name'  => $user['name'],
            'role'  => $user['role'],
            'email' => $user['email'],
        ]);
        header('Location: ' . (in_array($user['role'], ['admin','agent'], true) ? '/back/campaigns' : '/surveys'));
        exit;
    }

    public function logout(): void {
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
