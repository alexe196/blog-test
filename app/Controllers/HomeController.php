<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;

class HomeController
{
    public function index(): void
    {
        $categories = Category::getWithLatestPosts(3);

        View::render('home.tpl', [
            'pageTitle' => 'Blog',
            'categories' => $categories,
        ]);
    }
}