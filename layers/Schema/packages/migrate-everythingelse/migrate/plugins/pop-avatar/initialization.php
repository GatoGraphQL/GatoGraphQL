<?php
class PoP_Avatar_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-avatar', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
