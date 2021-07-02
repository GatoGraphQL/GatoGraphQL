<?php
class PoP_RelatedPostsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-relatedposts-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
