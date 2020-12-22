<?php
class PoP_CSSConverter_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cssconverter', false, dirname(plugin_basename(__FILE__)).'/languages');

        // Comment Leo 13/05/2018: Load it only before it is needed
        // /**
        //  * Load the Vendor Library
        //  */
        // // Comment Leo 10/07/2017: for some reason Composer's autoload doesn't work, so instead we load all the files manually
        // // require_once 'vendor/autoload.php';
        // require_once 'vendor-load.php';
        
        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
        
        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
