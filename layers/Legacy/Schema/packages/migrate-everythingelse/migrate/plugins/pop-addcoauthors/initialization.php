<?php
class PoP_AddCoauthors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addcoauthors', false, dirname(plugin_basename(__FILE__)).'/languages');

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
