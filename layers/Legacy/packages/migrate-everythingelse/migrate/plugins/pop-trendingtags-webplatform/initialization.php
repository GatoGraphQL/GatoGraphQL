<?php
class PoP_TrendingTagsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-trendingtags-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
