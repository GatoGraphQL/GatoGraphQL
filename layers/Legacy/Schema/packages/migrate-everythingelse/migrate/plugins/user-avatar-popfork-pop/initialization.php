<?php
class UserAvatarPoPForkPoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('user-avatar-popfork-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
