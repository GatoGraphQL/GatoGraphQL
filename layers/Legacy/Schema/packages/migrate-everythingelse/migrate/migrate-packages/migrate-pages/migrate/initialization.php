<?php
namespace PoPCMSSchema\Pages;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-pages', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';
    }
}
