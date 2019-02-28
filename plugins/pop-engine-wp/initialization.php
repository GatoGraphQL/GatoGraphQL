<?php
class PoP_EngineWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-engine-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
