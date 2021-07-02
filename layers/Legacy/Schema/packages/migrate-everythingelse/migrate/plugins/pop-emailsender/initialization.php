<?php
class PoP_EmailSender_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-emailsender', false, dirname(plugin_basename(__FILE__)).'/languages');
        
        /**
         * Load AWS
         */
        // require_once 'config/load.php';

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

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
