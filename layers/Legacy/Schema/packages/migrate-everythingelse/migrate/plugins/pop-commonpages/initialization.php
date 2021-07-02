<?php
class PoP_CommonPages_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-commonpages', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Global Variables and Configuration
         */
        include_once 'config/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the plugins' libraries
         */
        include_once 'plugins/load.php';
    }
}
