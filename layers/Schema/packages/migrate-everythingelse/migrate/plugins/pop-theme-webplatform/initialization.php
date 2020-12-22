<?php
class PoP_ThemeWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-theme-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins
         */
        include_once 'plugins/load.php';
    }
}
