<?php
class QTX_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('qtx-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * PoP Library
         */
        include_once 'library/load.php';

        /**
         * Plugins library
         */
        include_once 'plugins/load.php';
    }
}
