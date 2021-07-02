<?php
namespace PoPSchema\CustomPostMeta\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-postmeta-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';
    }
}
