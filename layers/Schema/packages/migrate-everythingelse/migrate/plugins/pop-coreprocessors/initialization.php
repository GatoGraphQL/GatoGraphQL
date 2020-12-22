<?php
class PoP_CoreProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-coreprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        include_once 'config/load.php';

        /**
         * Kernel Override
         */
        include_once 'kernel/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'pop-library/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins
         */
        include_once 'plugins/load.php';
    }
}
