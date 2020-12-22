<?php
class PoP_Multilingual_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-multilingual', false, dirname(plugin_basename(__FILE__)).'/languages');

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
