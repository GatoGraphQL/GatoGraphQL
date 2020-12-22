<?php
namespace PoPSchema\Menus\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-menus-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';
    }
}
