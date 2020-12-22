<?php
class PoP_ShareProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-share-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
