<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$pageTitle|default:'Blog'}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>

<header class="site-header">
    <div class="container">
        <a href="/" class="logo">Blog</a>
    </div>
</header>

<main class="site-main">
    {block name=content}{/block}
</main>

<footer class="site-footer">
    <div class="container">
        <p>Copyright ©2025. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>