<?php
use PoP\ComponentModel\Misc\RequestUtils;

/**
 * Override functions from WSL, from file plugins/wordpress-social-login/includes/widgets/wsl.error.pages.php
 * These functions do not work with the Template Manager, since they return HTML back, so mute them
 */

if (! function_exists('wsl_render_notice_page')) {
    function wsl_render_notice_page($message)
    {

        // Change PoP: no HTML here. Instead, add the message to the global errors
        RequestUtils::$errors[] = $message;
    }
}

if (! function_exists('wsl_render_error_page')) {
    function wsl_render_error_page($message, $notes = null, $provider = null, $api_error = null, $php_exception = null)
    {

        // Change PoP: only keep 'wsl_clear_user_php_session' and add the message to the global errors
        RequestUtils::$errors[] = $message;

        // keep these 2 LOC
        \PoP\Root\App::doAction('wsl_clear_user_php_session');
    }
}
