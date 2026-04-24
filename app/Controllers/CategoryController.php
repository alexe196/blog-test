<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use App\Models\Post;

class CategoryController
{
    public function show(int $id): void
    {
        $category = Category::find($id);

        if (!$category) {
            http_response_code(404);
            echo 'Category not found';
            return;
        }

        $sort = $_GET['sort'] ?? 'date';

        if (!in_array($sort, ['date', 'views'], true)) {
            $sort = 'date';
        }

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = 6;

        $totalPosts = Post::countByCategory($id);
        $totalPages = max(1, (int) ceil($totalPosts / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $posts = Post::getByCategoryPaginated($id, $sort, $page, $perPage);

        View::render('category.tpl', [
            'pageTitle' => $category['title'],
            'category' => $category,
            'posts' => $posts,
            'sort' => $sort,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}