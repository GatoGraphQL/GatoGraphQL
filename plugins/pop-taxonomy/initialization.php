<?php
namespace PoP\Taxonomy;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-taxonomy', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
