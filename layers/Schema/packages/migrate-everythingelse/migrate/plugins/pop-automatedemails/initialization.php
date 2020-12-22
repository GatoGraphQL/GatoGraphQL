<?php
class PoP_AutomatedEmails_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-automatedemails', false, dirname(plugin_basename(__FILE__)).'/languages');
        
        /**
         * Load the Library
         */
        include_once 'library/load.php';
        
        /**
         * Load the Plugins
         */
        include_once 'plugins/load.php';
    }
}
