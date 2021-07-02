<?php
class PoP_ResourceLoader_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-resourceloader', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plug-ins Library
         */
        include_once 'plugins/load.php';
    }
}
