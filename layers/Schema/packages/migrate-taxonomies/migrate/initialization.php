<?php
namespace PoPSchema\Taxonomies;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-taxonomies', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
