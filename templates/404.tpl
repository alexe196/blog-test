{extends file='layouts/main.tpl'}

{block name=content}
    <div class="container">
        <section class="not-found">
            <h1>404</h1>

            <p>{$message|default:'Page not found.'|escape}</p>

            <a href="/" class="button-link">Back to homepage</a>
        </section>
    </div>
{/block}