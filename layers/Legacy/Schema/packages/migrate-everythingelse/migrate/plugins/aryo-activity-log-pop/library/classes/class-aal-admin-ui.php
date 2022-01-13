<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AAL_PoP_Admin_Ui
{
    public function createAdminMenu()
    {
        $menu_capability = current_user_can('view_all_aryo_activity_log') ? 'view_all_aryo_activity_log' : 'edit_pages';
        
        $this->_screens['main'] = add_menu_page(_x('Notifications', 'Page and Menu Title', 'aal-pop'), _x('Notifications', 'Page and Menu Title', 'aryo-activity-log'), $menu_capability, 'pop_notifications_page', array( &$this, 'notificationsPageFunc' ), '', '2.1');
    }

    public function notificationsPageFunc()
    {

        // 1. Change the title from "Activity Log" to "Notifications"
        // 2. Hide the Export as CSV button, which is not needed and not working fine for Notifications ?>
        <style>
            h1.aal-page-title,
            #record-actions-submit {
                display: none;
            }
        </style>
        <div class="wrap">
            <h1 class="aal-pop-page-title"><?php _ex('Notifications', 'Page and Menu Title', 'aal-pop'); ?></h1>
        </div>
        <?php

        // Draw the table, hacking on the original functionality

        // Swap the DB table to the one for the notifications
        AAL_PoP_AdaptationUtils::swapDbTable();

        // Draw the original table, from the Admin UI instance
        $instance = AAL_Main::instance();
        $ui = $instance->ui ?? new AAL_Admin_Ui();
        $ui->activity_log_page_func();

        // Restore to the original AAL DB table
        AAL_PoP_AdaptationUtils::restoreDbTable();
    }
    
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('admin_menu', array( &$this, 'createAdminMenu' ), 20);
    }
    
    private function _is_elementor_installed()
    {
        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();

        return isset($installed_plugins[ $file_path ]);
    }
}
