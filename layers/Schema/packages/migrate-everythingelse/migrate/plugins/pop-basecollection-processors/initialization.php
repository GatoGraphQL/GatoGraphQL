<?php
class PoP_BaseCollectionProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-basecollection-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        // require_once 'plugins/load.php';
    }
}
