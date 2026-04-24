<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Post;

class PostController
{
    public function show(int $id): void
    {
        $post = Post::find($id);

        if (!$post) {
            http_response_code(404);
            echo 'Post not found';
            return;
        }

        Post::incrementViews($id);

        $post = Post::find($id);
        $categories = Post::getCategories($id);
        $similarPosts = Post::getSimilar($id, 3);

        View::render('post.tpl', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'categories' => $categories,
            'similarPosts' => $similarPosts,
        ]);
    }
}