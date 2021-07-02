<?php
class PoP_Mailer_AWS_Initialization
{
    public function initialize()
    {

        /**
         * Load the configuration
         */
        include_once 'config/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plug-ins Library
         */
        include_once 'plugins/load.php';
    }
}
