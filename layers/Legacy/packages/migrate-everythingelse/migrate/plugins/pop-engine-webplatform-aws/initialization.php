<?php
class PoP_WebPlatformEngine_AWS_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-engine-webplatform-aws', false, dirname(plugin_basename(__FILE__)).'/languages');
        
        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins library
         */
        include_once 'plugins/load.php';
    }
}
