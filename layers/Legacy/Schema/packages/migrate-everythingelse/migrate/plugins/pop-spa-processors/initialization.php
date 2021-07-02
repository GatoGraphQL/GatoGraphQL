<?php
class PoP_SPAProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-spa-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
