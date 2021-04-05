<?php
namespace PoPSchema\Tags;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-tags', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';
    }
}
