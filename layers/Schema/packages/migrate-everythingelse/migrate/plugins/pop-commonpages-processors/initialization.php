<?php
class PoP_CommonPagesProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-commonpages-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the plugins' libraries
         */
        include_once 'plugins/load.php';
    }
}
