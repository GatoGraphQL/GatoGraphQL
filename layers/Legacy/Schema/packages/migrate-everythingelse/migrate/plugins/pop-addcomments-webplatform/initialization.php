<?php
class PoP_AddCommentsWebPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addcomments-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
