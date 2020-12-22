<?php
class PoP_NoSearchCategoryPostsCreationProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-nosearchcategorypostscreation-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
