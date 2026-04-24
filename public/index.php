<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\PostController;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/category/{id}', [CategoryController::class, 'show']);
$router->get('/category/{id}/post/{id}', [PostController::class, 'show']);

$router->post('/post/{id}/comment', [PostController::class, 'comment']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
