<?php
class WPSC_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('wp-super-cache-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Library
         */
        include_once 'library/load.php';

        /**
         * Plugins
         */
        include_once 'plugins/load.php';
    }
}
