<?php
class PoP_Blog_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-blog', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        include_once 'config/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
