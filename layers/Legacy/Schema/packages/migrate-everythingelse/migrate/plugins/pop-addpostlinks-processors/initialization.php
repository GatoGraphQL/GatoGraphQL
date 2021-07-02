<?php
class PoP_AddPostLinksProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addpostlinks-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        // require_once 'plugins/load.php';
    }
}
