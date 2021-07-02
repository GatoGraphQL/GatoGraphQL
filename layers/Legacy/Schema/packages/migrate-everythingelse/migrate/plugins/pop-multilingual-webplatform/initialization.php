<?php
class PoP_MultilingualWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-multilingual-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Library
         */
        include_once 'library/load.php';
    }
}
