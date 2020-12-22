<?php
class CAP_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('cap-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Plugins
         */
        include_once 'plugins/load.php';

        /**
         * Library
         */
        include_once 'library/load.php';
    }
}
