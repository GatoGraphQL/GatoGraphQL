<?php
class PoP_LocationPostLinksCreation_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-locationpostlinkscreation', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        include_once 'config/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the PoP Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
