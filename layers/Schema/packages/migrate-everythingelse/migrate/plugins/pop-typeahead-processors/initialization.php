<?php
class PoP_TypeaheadProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-typeahead-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

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
