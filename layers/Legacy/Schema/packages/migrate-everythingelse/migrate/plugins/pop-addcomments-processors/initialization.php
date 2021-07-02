<?php
class PoP_AddCommentsProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addcomments-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
