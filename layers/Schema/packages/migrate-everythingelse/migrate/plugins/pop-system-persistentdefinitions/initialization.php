<?php
class PoP_PersistentDefinitionsSystem_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-system-persistentdefinitions', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

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
