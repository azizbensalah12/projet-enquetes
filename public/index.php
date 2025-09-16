<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use Core\Session;

Session::start();

$router = new Router();

// Public routes
$router->get('/', ['App\\Controllers\\HomeController', 'index']);
$router->get('/login', ['App\\Controllers\\AuthController', 'showLogin']);
$router->post('/login', ['App\\Controllers\\AuthController', 'login']);
$router->get('/logout', ['App\\Controllers\\AuthController', 'logout']);

// Protected routes (middleware-style guard inside controller methods)
// Users (admin)
$router->get('/admin/users', ['App\\Controllers\\UserController', 'index']);
$router->get('/admin/users/create', ['App\\Controllers\\UserController', 'create']);
$router->post('/admin/users', ['App\\Controllers\\UserController', 'store']);
$router->get('/admin/users/{id}/edit', ['App\\Controllers\\UserController', 'edit']);
$router->post('/admin/users/{id}', ['App\\Controllers\\UserController', 'update']);
$router->post('/admin/users/{id}/delete', ['App\\Controllers\\UserController', 'destroy']);

// Campaigns (admin + agent)
$router->get('/back/campaigns', ['App\\Controllers\\CampaignController', 'index']);
$router->get('/back/campaigns/create', ['App\\Controllers\\CampaignController', 'create']);
$router->post('/back/campaigns', ['App\\Controllers\\CampaignController', 'store']);
$router->get('/back/campaigns/{id}/edit', ['App\\Controllers\\CampaignController', 'edit']);
$router->post('/back/campaigns/{id}', ['App\\Controllers\\CampaignController', 'update']);
$router->post('/back/campaigns/{id}/delete', ['App\\Controllers\\CampaignController', 'destroy']);
$router->post('/back/campaigns/{id}/assign', ['App\\Controllers\\CampaignController', 'assignToClient']); 

// Questions (admin + agent)
$router->get('/back/questions', ['App\\Controllers\\QuestionController', 'index']);
$router->get('/back/questions/create', ['App\\Controllers\\QuestionController', 'create']);
$router->post('/back/questions', ['App\\Controllers\\QuestionController', 'store']);
$router->get('/back/questions/{id}/edit', ['App\\Controllers\\QuestionController', 'edit']);
$router->post('/back/questions/{id}', ['App\\Controllers\\QuestionController', 'update']);
$router->post('/back/questions/{id}/delete', ['App\\Controllers\\QuestionController', 'destroy']);

// Client (frontoffice)
$router->get('/surveys', ['App\\Controllers\\SurveyController', 'index']);
$router->get('/surveys/{id}', ['App\\Controllers\\SurveyController', 'show']);
$router->post('/surveys/{id}/submit', ['App\\Controllers\\SurveyController', 'submit']);

// Dispatch request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
