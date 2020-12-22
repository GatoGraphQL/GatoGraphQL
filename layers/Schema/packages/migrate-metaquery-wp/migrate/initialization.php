<?php
namespace PoPSchema\MetaQuery\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-metaquery-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
