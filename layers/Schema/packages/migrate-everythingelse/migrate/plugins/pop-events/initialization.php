<?php
class PoP_Migrate_Events_Initialization
{
    public function initialize()
    {
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
