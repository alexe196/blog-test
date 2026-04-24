{extends file='layouts/main.tpl'}

{block name=content}
    <div class="container">

        <article class="single-post">
            <img src="{$post.image|escape}" alt="{$post.title|escape}" class="single-post-image">

            <h1>{$post.title|escape}</h1>

            <div class="single-post-meta">
                <span>{$post.published_at|date_format:"%B %e, %Y"}</span>
                <span>Views: {$post.views}</span>
            </div>

            <p class="single-post-description">
                {$post.description|escape}
            </p>

            <div class="single-post-categories">
                Categories:

                {foreach from=$categories item=category}
                    <a href="/category/{$category.id}">{$category.title|escape}</a>
                {/foreach}
            </div>

            <div class="single-post-content">
                {$post.content|escape|nl2br}
            </div>
        </article>

        {if $similarPosts|count > 0}
            <section class="category-section">
                <div class="category-header">
                    <h2>Similar posts</h2>
                </div>

                <div class="posts-grid">
                    {foreach from=$similarPosts item=post}
                        <article class="post-card">
                            <a href="/category/{$category.id}/post/{$post.id}">
                                <img src="{$post.image|escape}" alt="{$post.title|escape}" class="post-image">
                            </a>

                            <h3>
                                <a href="/category/{$category.id}/post/{$post.id}">{$post.title|escape}</a>
                            </h3>

                            <div class="post-date">
                                {$post.published_at|date_format:"%B %e, %Y"}
                            </div>

                            <p>{$post.description|escape}</p>

                            <a href="/category/{$category.id}/post/{$post.id}" class="read-more">Continue Reading</a>
                        </article>
                    {/foreach}
                </div>
            </section>
        {/if}

    </div>
{/block}