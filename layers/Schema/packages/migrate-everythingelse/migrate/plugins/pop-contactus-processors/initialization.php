<?php
class PoP_ContactUsProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-contactus-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
