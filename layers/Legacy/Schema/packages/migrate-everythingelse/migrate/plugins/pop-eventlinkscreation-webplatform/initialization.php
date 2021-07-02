<?php
class PoP_EventLinksCreationWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-eventlinkscreation-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
