<?php
class PoP_ApplicationWPWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-applicationwp-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
