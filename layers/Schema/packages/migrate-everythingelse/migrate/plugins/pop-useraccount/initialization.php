<?php
namespace PoP\UserAccount;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-useraccount', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';
    }
}
