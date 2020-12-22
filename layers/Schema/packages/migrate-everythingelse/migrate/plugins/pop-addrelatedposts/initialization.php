<?php
class PoP_AddRelatedPosts_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addrelatedposts', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
