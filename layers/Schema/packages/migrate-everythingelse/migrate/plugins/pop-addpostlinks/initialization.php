<?php
class PoP_AddPostLinks_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addpostlinks', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
