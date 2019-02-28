<?php
namespace PoP\CMS\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cms-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
