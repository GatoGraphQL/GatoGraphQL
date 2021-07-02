<?php
class PoP_UserPlatformWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userplatformwp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
