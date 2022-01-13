<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Add the jQuery script after displaying the page HTML, so move it out of the header
\PoP\Root\App::getHookManager()->addAction('init', 'popReorderHeadScripts', 10000);
function popReorderHeadScripts()
{
    
    // If PoP SSR is not defined, then there is no PoP_SSR_ServerUtils
    if (defined('POP_SSR_INITIALIZED')) {
        if (PoP_SSR_ServerUtils::includeScriptsAfterHtml()) {
            // Move the head scripts to the footer
            // Set in file wp-includes/default-filters.php
            \PoP\Root\App::getHookManager()->removeAction('wp_head', 'wp_print_head_scripts', 9);
            \PoP\Root\App::getHookManager()->addAction('wp_footer', 'wp_print_head_scripts', 1);

            // Move the `window._wpemojiSettings` <script> to the footer
            \PoP\Root\App::getHookManager()->removeAction('wp_head', 'print_emoji_detection_script', 7);
            \PoP\Root\App::getHookManager()->addAction('wp_footer', 'print_emoji_detection_script', 0);
        }
    }
}
