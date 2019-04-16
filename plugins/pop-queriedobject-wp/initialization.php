<?php
namespace PoP\QueriedObject\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-queriedobject-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';
    }
}
