<?php
namespace PoP\EditPosts;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-editposts', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';
    }
}
