<?php
namespace PoPSchema\Menus;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-menus', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';
    }
}
