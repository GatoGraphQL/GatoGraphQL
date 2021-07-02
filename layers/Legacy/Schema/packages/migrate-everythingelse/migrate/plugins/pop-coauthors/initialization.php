<?php
class PoP_Coauthors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-coauthors', false, dirname(plugin_basename(__FILE__)).'/languages');

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
