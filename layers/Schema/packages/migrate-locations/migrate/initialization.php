<?php
class PoP_Locations_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-locations', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
