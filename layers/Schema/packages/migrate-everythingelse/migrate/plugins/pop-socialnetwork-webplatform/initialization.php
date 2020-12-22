<?php
class PoP_SocialNetworkWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-socialnetwork-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
