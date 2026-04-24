<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

$pdo = Database::connection();

$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
$pdo->exec('TRUNCATE TABLE category_post');
$pdo->exec('TRUNCATE TABLE posts');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS=1');

$categories = [
    [
        'title' => 'Category 1',
        'description' => 'This is the first blog category with fresh articles and useful information.',
    ],
    [
        'title' => 'Category 2',
        'description' => 'This category contains posts about lifestyle, daily stories and practical tips.',
    ],
    [
        'title' => 'Category 3',
        'description' => 'Articles, guides and interesting blog posts collected in one place.',
    ],
    [
        'title' => 'Category 4',
        'description' => 'Latest updates, ideas and helpful materials for readers.',
    ],
];

$categoryIds = [];

$categoryStatement = $pdo->prepare(
    '
    INSERT INTO categories (title, description, created_at)
    VALUES (:title, :description, NOW())
'
);

foreach ($categories as $category) {
    $categoryStatement->execute([
        'title' => $category['title'],
        'description' => $category['description'],
    ]);

    $categoryIds[] = (int)$pdo->lastInsertId();
}

$postTitles = [
    'Lorem ipsum dolor sit amet',
    'Simple blog post example',
    'How to create a clean design',
    'Useful tips for everyday life',
    'Modern web development basics',
    'Understanding simple PHP apps',
    'Working with MySQL and PDO',
    'Template rendering with Smarty',
    'Clean structure for small projects',
    'Building a blog from scratch',
    'Category based article system',
    'Popular posts and views',
    'Pagination and sorting example',
    'Writing readable PHP code',
    'Simple website architecture',
    'Latest news and updates',
    'Practical guide for beginners',
    'Design ideas for blog pages',
    'Good structure without framework',
    'Final article example',
    'Another useful blog story',
    'PHP routing from scratch',
    'Database relations explained',
    'How similar posts work',
];

$images = [
    '/uploads/post-1.jpg',
    '/uploads/post-2.jpg',
    '/uploads/post-3.jpg',
];

$postStatement = $pdo->prepare(
    '
    INSERT INTO posts (
        image,
        title,
        description,
        content,
        views,
        published_at,
        created_at
    ) VALUES (
        :image,
        :title,
        :description,
        :content,
        :views,
        :published_at,
        NOW()
    )
'
);

$pivotStatement = $pdo->prepare(
    '
    INSERT INTO category_post (category_id, post_id)
    VALUES (:category_id, :post_id)
'
);

foreach ($postTitles as $index => $title) {
    $description = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo sunt tempora dolor laudantium sed optio.';

    $content = <<<TEXT
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo sunt tempora dolor laudantium sed optio, explicabo ad deleniti impedit facilis fugit recusandae.

This is a simple blog article created for the test task. The goal of this project is to show clean PHP code, MySQL usage, Smarty templates and simple routing without using a framework.

The article page displays full post information, categories, views and similar posts from the same category.
TEXT;

    $publishedAt = date('Y-m-d H:i:s', strtotime('-' . $index . ' days'));

    $postStatement->execute([
        'image' => $images[$index % count($images)],
        'title' => $title,
        'description' => $description,
        'content' => $content,
        'views' => rand(10, 500),
        'published_at' => $publishedAt,
    ]);

    $postId = (int)$pdo->lastInsertId();

    $mainCategoryId = $categoryIds[$index % count($categoryIds)];

    $pivotStatement->execute([
        'category_id' => $mainCategoryId,
        'post_id' => $postId,
    ]);

    if ($index % 5 === 0) {
        $secondCategoryId = $categoryIds[($index + 1) % count($categoryIds)];

        if ($secondCategoryId !== $mainCategoryId) {
            $pivotStatement->execute([
                'category_id' => $secondCategoryId,
                'post_id' => $postId,
            ]);
        }
    }
}

echo "Database seeded successfully.\n";