<?php
class PPP_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('ppp-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Library
         */
        include_once 'library/load.php';

        /**
         * Plugins library
         */
        include_once 'plugins/load.php';
    }
}
