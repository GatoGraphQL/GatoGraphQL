<?php
class PoP_LocationsProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-locations-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

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
