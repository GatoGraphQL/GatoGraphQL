<?php
class PoP_AddCommentsTinyMCEProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addcomments-tinymce-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'pop-library/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
