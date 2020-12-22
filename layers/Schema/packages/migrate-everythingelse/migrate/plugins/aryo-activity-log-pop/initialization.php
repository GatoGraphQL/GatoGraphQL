<?php
class AAL_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('aal-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        // Plugin set-up
        $this->setup();

        /**
         * Library
         */
        include_once 'library/load.php';

        /**
         * Plugins Library
         */
        include_once 'plugins/load.php';
    }

    protected function setup()
    {

        // set up our DB names
        global $wpdb;
        $wpdb->pop_notifications = $wpdb->prefix.'pop_notifications';
        $wpdb->pop_notifications_status = $wpdb->prefix.'pop_notifications_status';
    }
}
