<?php
namespace PoP\CMS\WP\Frontend;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cmsfrontend-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';
    }
}
