<?php
class PoP_AvatarFoundation_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-avatar-foundation', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
