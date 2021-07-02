<?php
class PoP_MultidomainProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-multidomain-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'pop-library/load.php';
    }
}
