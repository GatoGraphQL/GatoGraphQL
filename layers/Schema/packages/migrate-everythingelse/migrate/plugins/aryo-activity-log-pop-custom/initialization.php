<?php
class AAL_PoPCustom_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('aal-pop-custom', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
