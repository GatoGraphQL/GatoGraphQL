<?php
class PoP_Forms_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-forms', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
