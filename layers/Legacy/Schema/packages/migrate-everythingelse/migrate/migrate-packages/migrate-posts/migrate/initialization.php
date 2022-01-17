<?php
namespace PoPCMSSchema\Posts;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-posts', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
