<?php
class PoP_AddCommentsTinyMCE_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-addcomments-tinymce', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

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
