<?php
class PoP_CategoryPostsCreationWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-categorypostscreation-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
