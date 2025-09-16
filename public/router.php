<?php
// Router pour le serveur PHP intégré.
// Sert les fichiers statiques; sinon, charge index.php (front controller).
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

if ($uri !== '/' && file_exists($file) && is_file($file)) {
    return false; // laisser le serveur servir /assets, images, etc.
}
require __DIR__ . '/index.php';
