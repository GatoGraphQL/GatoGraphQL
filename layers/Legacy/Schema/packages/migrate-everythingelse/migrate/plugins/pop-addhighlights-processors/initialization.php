<?php
class PoP_AddHighlightsProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addhighlights-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the PoP Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
