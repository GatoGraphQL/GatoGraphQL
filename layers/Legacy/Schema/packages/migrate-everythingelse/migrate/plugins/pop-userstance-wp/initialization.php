<?php
class PoP_UserStanceWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userstance-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Set-up
         */
        include_once 'setup/load.php';
    }
}
