<?php
class PoP_Share_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-share', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
