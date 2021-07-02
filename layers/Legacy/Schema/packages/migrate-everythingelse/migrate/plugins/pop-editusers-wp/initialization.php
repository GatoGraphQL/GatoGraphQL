<?php
namespace PoP\EditUsers\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-editusers-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';
    }
}
