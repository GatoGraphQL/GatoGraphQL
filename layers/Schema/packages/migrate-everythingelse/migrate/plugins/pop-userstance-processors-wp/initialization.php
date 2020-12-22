<?php
class PoP_UserStanceProcessorsWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-userstance-processors-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
