{extends file='layouts/main.tpl'}

{block name=content}
    <div class="container">

        {foreach from=$categories item=category}
            <section class="category-section">
                <div class="category-header">
                    <h2>{$category.title|escape}</h2>
                    <a href="/category/{$category.id}" class="view-all">View All</a>
                </div>

                <div class="posts-grid">
                    {foreach from=$category.posts item=post}
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
        {/foreach}

    </div>
{/block}