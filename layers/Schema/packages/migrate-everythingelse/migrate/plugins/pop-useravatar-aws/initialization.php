<?php
class PoP_UserAvatar_AWS_Initialization
{
    public function initialize()
    {

        // load_plugin_textdomain('pop-useravatar-aws', false, dirname(plugin_basename(__FILE__)).'/languages');
        
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
