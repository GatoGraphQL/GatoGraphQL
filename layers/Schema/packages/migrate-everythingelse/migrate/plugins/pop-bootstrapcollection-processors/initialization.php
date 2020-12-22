<?php
class PoP_BootstrapCollectionProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-bootstrapcollection-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Library
         */
        include_once 'plugins/load.php';
    }
}
