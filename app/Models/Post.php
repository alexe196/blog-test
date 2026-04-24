<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Post
{
    public static function find(int $id): ?array
    {
        $pdo = Database::connection();

        $statement = $pdo->prepare(
            '
            SELECT *
            FROM posts
            WHERE id = :id
            LIMIT 1
        '
        );

        $statement->execute([
            'id' => $id,
        ]);

        $post = $statement->fetch(PDO::FETCH_ASSOC);

        return $post ?: null;
    }

    public static function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $pdo = Database::connection();

        $statement = $pdo->prepare(
            '
            SELECT p.*
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
            WHERE cp.category_id = :category_id
            ORDER BY p.published_at DESC
            LIMIT ' . (int)$limit
        );

        $statement->execute([
            'category_id' => $categoryId,
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByCategoryPaginated(
        int $categoryId,
        string $sort,
        int $page,
        int $perPage
    ): array {
        $pdo = Database::connection();

        $offset = ($page - 1) * $perPage;

        $orderBy = match ($sort) {
            'views' => 'p.views DESC',
            default => 'p.published_at DESC',
        };

        $statement = $pdo->prepare(
            "
            SELECT p.*
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
            WHERE cp.category_id = :category_id
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        "
        );

        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByCategory(int $categoryId): int
    {
        $pdo = Database::connection();

        $statement = $pdo->prepare(
            '
            SELECT COUNT(*) as total
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
            WHERE cp.category_id = :category_id
        '
        );

        $statement->execute([
            'category_id' => $categoryId,
        ]);

        return (int)$statement->fetchColumn();
    }

    public static function incrementViews(int $id): void
    {
        $pdo = Database::connection();

        $statement = $pdo->prepare(
            '
            UPDATE posts
            SET views = views + 1
            WHERE id = :id
        '
        );

        $statement->execute([
            'id' => $id,
        ]);
    }

    public static function getCategories(int $postId): array
    {
        $pdo = Database::connection();

        $statement = $pdo->prepare(
            '
            SELECT c.*
            FROM categories c
            INNER JOIN category_post cp ON cp.category_id = c.id
            WHERE cp.post_id = :post_id
            ORDER BY c.title ASC
        '
        );

        $statement->execute([
            'post_id' => $postId,
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSimilar(int $postId, int $limit = 3): array
    {
        $pdo = Database::connection();

        $limit = max(1, min($limit, 12));

        $sql = "
        SELECT DISTINCT p.*
        FROM posts p
        INNER JOIN category_post cp ON cp.post_id = p.id
        WHERE cp.category_id IN (
            SELECT cp2.category_id
            FROM category_post cp2
            WHERE cp2.post_id = ?
        )
        AND p.id != ?
        ORDER BY p.published_at DESC
        LIMIT {$limit}
    ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $postId,
            $postId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}