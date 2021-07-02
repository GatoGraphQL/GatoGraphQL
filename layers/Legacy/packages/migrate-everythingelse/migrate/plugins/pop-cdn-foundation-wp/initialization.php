<?php
class PoP_CDNFoundationWP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-cdn-foundation-wp', false, dirname(plugin_basename(__FILE__)).'/languages');
        
        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
