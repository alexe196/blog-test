<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$db = $config['db'];

$dsn = sprintf(
    'mysql:host=%s;port=%s;charset=%s',
    $db['host'],
    $db['port'],
    $db['charset']
);

try {
    $pdo = new PDO($dsn, $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sqlFile = __DIR__ . '/schema.sql';

    if (! file_exists($sqlFile)) {
        throw new RuntimeException('schema.sql not found.');
    }

    $sql = file_get_contents($sqlFile);

    if ($sql === false || trim($sql) === '') {
        throw new RuntimeException('schema.sql is empty.');
    }

    $pdo->exec($sql);

    echo "Migration completed successfully.\n";
} catch (Throwable $exception) {
    echo "Migration failed: " . $exception->getMessage() . "\n";
    exit(1);
}
