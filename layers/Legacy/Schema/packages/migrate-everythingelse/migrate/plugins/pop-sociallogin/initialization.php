<?php
class PoP_SocialLogin_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('wsl-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Library
         */
        include_once 'library/load.php';

        /**
         * Plugins
         */
        include_once 'plugins/load.php';
    }
}
