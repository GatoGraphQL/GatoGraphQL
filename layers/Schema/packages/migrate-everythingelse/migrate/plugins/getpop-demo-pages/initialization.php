<?php
class GetPoPDemo_Pages_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('getpop-demo-pages', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Global Variables and Configuration from CUSTOM folder
         */
        include_once 'config/load.php';
        
        /**
         * Load the Plug-ins
         */
        include_once 'plugins/load.php';
    }
}
