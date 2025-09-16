<?php
namespace Core;

abstract class Controller {
    protected function view(string $view, array $data = [], string $layout = 'back'): void {
        extract($data);
        $viewFile = dirname(__DIR__) . '/Views/' . $view . '.php';
        $layoutFile = dirname(__DIR__) . '/Views/layouts/' . $layout . '.php';
        ob_start();
        if (file_exists($viewFile)) require $viewFile; else echo "View not found: $viewFile";
        $content = ob_get_clean();
        require $layoutFile;
    }

    protected function redirect(string $path): void {
        header('Location: ' . $path);
        exit;
    }
}
