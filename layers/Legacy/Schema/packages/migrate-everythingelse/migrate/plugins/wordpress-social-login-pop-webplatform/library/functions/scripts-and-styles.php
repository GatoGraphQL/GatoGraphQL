<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Load the .js file
\PoP\Root\App::getHookManager()->addAction("init", "gdWslScriptsAndStyles");
function gdWslScriptsAndStyles()
{
    if (!is_admin()) {
        \PoP\Root\App::getHookManager()->removeAction('wp_enqueue_scripts', 'wsl_add_stylesheets');
        \PoP\Root\App::getHookManager()->removeAction('login_enqueue_scripts', 'wsl_add_stylesheets');
        \PoP\Root\App::getHookManager()->removeAction('wp_enqueue_scripts', 'wsl_add_javascripts');
        \PoP\Root\App::getHookManager()->removeAction('login_enqueue_scripts', 'wsl_add_javascripts');
    }
}
