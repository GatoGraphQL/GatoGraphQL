<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Make plugin Activity Log compatible with PoP
 */
// Re-assign what instances from what classes are needed
$instance = AAL_Main::instance();
// Removes the Settings page:
$instance->settings = new AAL_PoP_Settings();
// Removes the Notifications functionalities:
$instance->notifications = new AAL_PoP_Notifications();

// Remove unwanted ads
HooksAPIFacade::getInstance()->removeAction('admin_notices', array( &$instance->ui, 'admin_notices' ));
HooksAPIFacade::getInstance()->removeAction('wp_ajax_aal_install_elementor_set_admin_notice_viewed', array( &$instance->ui, 'ajax_aal_install_elementor_set_admin_notice_viewed' ));
