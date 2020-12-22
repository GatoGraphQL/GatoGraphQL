<?php
class GetPoPDemo_PagesProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('getpop-demo-pages-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Plug-ins
         */
        include_once 'plugins/load.php';
    }
}
