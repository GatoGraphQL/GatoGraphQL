<?php
class PoP_AWS_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-aws', false, dirname(plugin_basename(__FILE__)).'/languages');
        
        /**
         * Load AWS
         */
        include_once 'config/load.php';

        /**
         * Load AWS SDK
         */
        include_once 'includes/load.php';

        /**
         * Load the library
         */
        include_once 'library/load.php';
    }
}
