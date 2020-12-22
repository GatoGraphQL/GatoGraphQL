<?php
class PoP_BlogWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-blog-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
