<?php
class ACF_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('acf-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Library
         */
        include_once 'library/load.php';
    }
}
