<?php
namespace PoP\Engine;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-engine', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Server first, so we can access class ServerUtils
         * And its required library first
         */
        include_once 'server/load.php';

        /**
         * Load the Temporary Hacks
         */
        include_once 'temporary-hacks/load.php';

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
