<?php
namespace PoPSchema\Users;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-users', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        include_once 'config/load.php';

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
