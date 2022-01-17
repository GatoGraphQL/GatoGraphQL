<?php
namespace PoPCMSSchema\Media;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-media', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';
    }
}
