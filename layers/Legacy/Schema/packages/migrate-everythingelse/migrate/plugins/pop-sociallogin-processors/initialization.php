<?php
class PoP_SocialLoginProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('wsl-popprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Plugins
         */
        include_once 'plugins/load.php';

        /**
         * PoP Library
         */
        include_once 'pop-library/load.php';
    }
}
