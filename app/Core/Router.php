<?php
namespace Core;

class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $path, $handler) { $this->routes['GET'][$this->normalize($path)] = $handler; }
    public function post(string $path, $handler) { $this->routes['POST'][$this->normalize($path)] = $handler; }

    private function normalize(string $path): string { return rtrim($path, '/') ?: '/'; }

    public function dispatch(string $method, string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = $this->normalize($path);

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([a-zA-Z0-9_\-]+)', $route);
            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                array_shift($matches);
                [$class, $action] = $handler;
                $controller = new $class;
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
}
