<?php
namespace PoP\CMS\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cms-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Vendors
         */
        include_once dirname(__FILE__).'/vendor/autoload.php';

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
