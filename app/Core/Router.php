<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array|callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array|callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array|callable $handler): void
    {
        $path = rtrim($path, '/') ?: '/';

        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        $method = strtoupper($method);

        if (! isset($this->routes[$method])) {
            $this->sendNotFound();
            return;
        }

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = $this->convertRouteToPattern($route);

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);

                $this->runHandler($handler, $matches);
                return;
            }
        }

        if ($this->routeExistsForAnotherMethod($path, $method)) {
            $this->sendMethodNotAllowed();
            return;
        }

        $this->sendNotFound();
    }

    private function runHandler(array|callable $handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        [$controllerClass, $controllerMethod] = $handler;

        $controller = new $controllerClass();

        $controller->{$controllerMethod}(...$params);
    }

    private function routeExistsForAnotherMethod(string $path, string $currentMethod): bool
    {
        foreach ($this->routes as $method => $routes) {
            if ($method === $currentMethod) {
                continue;
            }

            foreach ($routes as $route => $handler) {
                $pattern = $this->convertRouteToPattern($route);

                if (preg_match($pattern, $path)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function convertRouteToPattern(string $route): string
    {
        $route = rtrim($route, '/') ?: '/';

        $pattern = preg_replace(
            '#\{[a-zA-Z_][a-zA-Z0-9_]*\}#',
            '([0-9]+)',
            $route
        );

        return '#^' . $pattern . '$#';
    }

    private function sendNotFound(): void
    {
        http_response_code(404);

        View::render('404.tpl', [
            'pageTitle' => 'Page not found',
            'message' => 'The requested page could not be found.',
        ]);
    }

    private function sendMethodNotAllowed(): void
    {
        http_response_code(405);

        View::render('404.tpl', [
            'pageTitle' => 'Method not allowed',
            'message' => 'This request method is not allowed for the selected page.',
        ]);
    }
}