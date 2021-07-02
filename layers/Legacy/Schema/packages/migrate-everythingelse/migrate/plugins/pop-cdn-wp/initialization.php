<?php
class PoP_CDNWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cdn-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Plugins
         */
        include_once 'plugins/load.php';
    }
}
