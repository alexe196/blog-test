{extends file='layouts/main.tpl'}

{block name=content}
    <div class="container">

        <section class="page-heading">
            <h1>{$category.title|escape}</h1>
            <p>{$category.description|escape}</p>
        </section>

        <div class="sort-box">
            <span>Sort by:</span>

            <a href="/category/{$category.id}?sort=date"
               class="{if $sort == 'date'}active{/if}">
                Date
            </a>

            <a href="/category/{$category.id}?sort=views"
               class="{if $sort == 'views'}active{/if}">
                Views
            </a>
        </div>

        {if $posts|count > 0}
            <div class="posts-grid">
                {foreach from=$posts item=post}
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

                        <div class="post-views">
                            Views: {$post.views}
                        </div>

                        <p>{$post.description|escape}</p>

                        <a href="/category/{$category.id}/post/{$post.id}" class="read-more">Continue Reading</a>
                    </article>
                {/foreach}
            </div>
        {else}
            <p>No posts found.</p>
        {/if}

        {if $totalPages > 1}
            <div class="pagination">
                {section name=p start=1 loop=$totalPages+1}
                    <a href="/category/{$category.id}?sort={$sort}&page={$smarty.section.p.index}"
                       class="{if $page == $smarty.section.p.index}active{/if}">
                        {$smarty.section.p.index}
                    </a>
                {/section}
            </div>
        {/if}

    </div>
{/block}