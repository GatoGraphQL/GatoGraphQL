<?php
class PoP_MultilingualProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-multilingual-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
