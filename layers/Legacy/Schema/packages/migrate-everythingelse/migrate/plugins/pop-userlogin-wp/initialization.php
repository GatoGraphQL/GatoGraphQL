<?php
class PoP_UserLoginWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userloginwp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
