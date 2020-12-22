<?php
class PoPTheme_Wassup_GetPoPDemo_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('poptheme-wassup-getpop-demo', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Related plug-ins
         */
        include_once 'plugins/load.php';
    }
}
