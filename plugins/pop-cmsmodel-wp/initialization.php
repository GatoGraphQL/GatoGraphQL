<?php
namespace PoP\CMSModel\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cmsmodel-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        // require_once 'library/load.php';
    }
}
