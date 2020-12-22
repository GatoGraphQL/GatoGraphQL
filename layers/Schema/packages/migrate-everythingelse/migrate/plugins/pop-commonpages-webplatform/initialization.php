<?php
class PoP_CommonPagesWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-commonpages-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

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
