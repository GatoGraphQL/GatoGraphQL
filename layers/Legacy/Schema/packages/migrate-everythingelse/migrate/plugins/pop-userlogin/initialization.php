<?php
class PoP_UserLogin_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userlogin', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Interfaces
         */
        // require_once 'interfaces/load.php';

        /**
         * Load the Kernel
         */
        // require_once 'kernel/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'pop-library/load.php';

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
