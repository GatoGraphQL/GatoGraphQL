<?php
namespace PoPCMSSchema\Meta;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-meta', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
