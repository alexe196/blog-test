<?php

namespace App\Core;

use Smarty\Smarty;

class View
{
    public static function render(string $template, array $data = []): void
    {
        $smarty = new Smarty();

        $smarty->setTemplateDir(__DIR__ . '/../../templates');
        $smarty->setCompileDir(__DIR__ . '/../../var/compile');
        $smarty->setCacheDir(__DIR__ . '/../../var/cache');

        foreach ($data as $key => $value) {
            $smarty->assign($key, $value);
        }

        $smarty->display($template);
    }
}
