<?php
class PoP_CaptchaProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-captcha-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plug-ins Library
         */
        include_once 'plugins/load.php';
    }
}
