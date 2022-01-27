<?php
namespace PoPCMSSchema\CustomPostMeta;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-postmeta', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
