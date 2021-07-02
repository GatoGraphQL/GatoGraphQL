<?php
class WSL_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('wsl-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Plugins
         */
        include_once 'plugins/load.php';
    }
}
