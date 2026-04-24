<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Category
{
    public static function find(int $id): ?array
    {
        $pdo = Database::connection();

        $statement = $pdo->prepare('
            SELECT *
            FROM categories
            WHERE id = :id
            LIMIT 1
        ');

        $statement->execute([
            'id' => $id,
        ]);

        $category = $statement->fetch(PDO::FETCH_ASSOC);

        return $category ?: null;
    }

    public static function getCategoriesWithPosts(): array
    {
        $pdo = Database::connection();

        $statement = $pdo->query('
            SELECT DISTINCT c.*
            FROM categories c
            INNER JOIN category_post cp ON cp.category_id = c.id
            INNER JOIN posts p ON p.id = cp.post_id
            ORDER BY c.id ASC
        ');

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getWithLatestPosts(int $limit = 3): array
    {
        $categories = self::getCategoriesWithPosts();

        foreach ($categories as &$category) {
            $category['posts'] = Post::getLatestByCategory((int) $category['id'], $limit);
        }

        return $categories;
    }
}