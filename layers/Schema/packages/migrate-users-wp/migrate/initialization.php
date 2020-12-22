<?php
namespace PoPSchema\Users\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-users-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';

        /**
         * Load the Plugins library
         */
        include_once 'plugins/load.php';
    }
}
