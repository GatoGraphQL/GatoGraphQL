<?php
namespace PoP\CMS;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cms', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';
    }
}
