<?php
class PoP_LocationPostsWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-locationposts-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Set-up
         */
        include_once 'setup/load.php';
    }
}
