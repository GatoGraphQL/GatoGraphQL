<?php
class PoP_CommonUserRolesProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-commonuserroles-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        include_once 'config/load.php';

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
