<?php
class PoP_FormsProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-forms-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
