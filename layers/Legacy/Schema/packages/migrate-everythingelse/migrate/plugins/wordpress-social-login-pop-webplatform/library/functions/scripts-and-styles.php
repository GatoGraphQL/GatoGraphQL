<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Load the .js file
HooksAPIFacade::getInstance()->addAction("init", "gdWslScriptsAndStyles");
function gdWslScriptsAndStyles()
{
    if (!is_admin()) {
        HooksAPIFacade::getInstance()->removeAction('wp_enqueue_scripts', 'wsl_add_stylesheets');
        HooksAPIFacade::getInstance()->removeAction('login_enqueue_scripts', 'wsl_add_stylesheets');
        HooksAPIFacade::getInstance()->removeAction('wp_enqueue_scripts', 'wsl_add_javascripts');
        HooksAPIFacade::getInstance()->removeAction('login_enqueue_scripts', 'wsl_add_javascripts');
    }
}
