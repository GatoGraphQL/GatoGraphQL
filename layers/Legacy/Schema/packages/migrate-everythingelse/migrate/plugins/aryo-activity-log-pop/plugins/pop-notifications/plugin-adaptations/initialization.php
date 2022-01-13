<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Make plugin Activity Log compatible with PoP
 */
// Execute them in init so we allow classes to hook into 'AAL_PoP_Hooks'
\PoP\Root\App::getHookManager()->addAction('init', 'aalPopInitAdaptations', 0);
function aalPopInitAdaptations()
{

    // Re-assign what instances from what classes are needed
    $instance = AAL_Main::instance();
    // PoP implementation of the API, overriding the delete functions to not delete data from the PoP Notifications table
    $instance->api = new AAL_PoP_API();
    // Hooks to create our own notifications. No need to assign it under $instance->hooks, since that variable is never accessed in AAL anyway, and just creating the object triggers the execution of all code
    /*$instance->hooks = */new AAL_PoP_Hooks();
    // Adds the menu item to view the notifications. No need to assign it under $instance->hooks, since that variable is never accessed in AAL anyway, and just creating the object triggers the execution of all code
    /*$instance->ui = */new AAL_PoP_Admin_Ui();
}
