<?php
class GADWP_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('gadwp-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Plugins
         */
        include_once 'plugins/load.php';
    }
}
