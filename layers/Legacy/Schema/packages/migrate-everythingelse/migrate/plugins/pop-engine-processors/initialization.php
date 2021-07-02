<?php
class PoP_EngineProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-engine-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'kernel/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
