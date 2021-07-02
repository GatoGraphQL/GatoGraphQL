<?php
class PoP_NoSearchCategoryPostsCreationWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-nosearchcategorypostscreation-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
