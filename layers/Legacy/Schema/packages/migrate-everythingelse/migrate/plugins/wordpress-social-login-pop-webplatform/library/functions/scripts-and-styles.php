<?php

// Load the .js file
\PoP\Root\App::addAction("init", "gdWslScriptsAndStyles");
function gdWslScriptsAndStyles()
{
    if (!is_admin()) {
        \PoP\Root\App::removeAction('wp_enqueue_scripts', 'wsl_add_stylesheets');
        \PoP\Root\App::removeAction('login_enqueue_scripts', 'wsl_add_stylesheets');
        \PoP\Root\App::removeAction('wp_enqueue_scripts', 'wsl_add_javascripts');
        \PoP\Root\App::removeAction('login_enqueue_scripts', 'wsl_add_javascripts');
    }
}
