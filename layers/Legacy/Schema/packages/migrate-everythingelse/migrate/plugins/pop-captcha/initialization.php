<?php
class PoP_Captcha_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-captcha', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
