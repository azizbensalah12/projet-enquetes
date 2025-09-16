<?php
require __DIR__ . '/../vendor/autoload.php';
use Core\DB;

header('Content-Type: text/plain; charset=utf-8');

$email = 'admin@example.com';
$pass  = 'admin123';

$db = DB::conn();
$stmt = $db->prepare("SELECT id,name,email,password,role FROM users WHERE email=:email LIMIT 1");
$stmt->execute(['email'=>$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "EMAIL LOOKUP: "; var_export((bool)$user); echo PHP_EOL;
if ($user) {
  echo "HASH (first 7): " . substr($user['password'],0,7) . PHP_EOL;
  echo "VERIFY: "; var_export(password_verify($pass, $user['password'])); echo PHP_EOL;
}
