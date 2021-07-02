<?php
class PoP_LocationPostCategoryLayoutsProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-locationpostcategorylayouts-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plug-ins Library
         */
        include_once 'plugins/load.php';
    }
}
