<?php
namespace PoP\QueriedObject;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-queriedobject', false, dirname(plugin_basename(__FILE__)).'/languages');

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
