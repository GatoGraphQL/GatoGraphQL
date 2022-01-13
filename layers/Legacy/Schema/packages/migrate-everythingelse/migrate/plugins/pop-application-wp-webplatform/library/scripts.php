<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * scripts.php
 */
// Remove all the scripts
if (PoP_WebPlatform_ServerUtils::disableJs()) {
    HooksAPIFacade::getInstance()->addAction('wp_print_scripts', 'popApplicationWpRemoveAllScripts', PHP_INT_MAX);

    // Avoid to add scripts in the footer
    HooksAPIFacade::getInstance()->removeAction('wp_footer', 'wp_print_footer_scripts', 20);
    HooksAPIFacade::getInstance()->removeAction('wp_print_footer_scripts', '_wp_footer_scripts');

    // Do not add the Media templates
    HooksAPIFacade::getInstance()->addAction('wp_enqueue_media', 'popApplicationWpRemoveMediaTemplates');
}
function popApplicationWpRemoveAllScripts()
{
    global $wp_scripts;
    $wp_scripts->queue = array();
}
function popApplicationWpRemoveMediaTemplates()
{
    HooksAPIFacade::getInstance()->removeAction('wp_footer', 'wp_print_media_templates');
}
