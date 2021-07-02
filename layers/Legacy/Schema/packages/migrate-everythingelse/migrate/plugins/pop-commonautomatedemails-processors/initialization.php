<?php
class PoP_CommonAutomatedEmailsProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-commonautomatedemails-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
