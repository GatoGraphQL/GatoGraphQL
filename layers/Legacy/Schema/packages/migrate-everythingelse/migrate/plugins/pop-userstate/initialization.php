<?php

class PoP_UserState_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userstate', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
