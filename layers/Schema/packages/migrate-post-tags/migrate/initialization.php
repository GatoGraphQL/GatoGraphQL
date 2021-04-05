<?php
namespace PoPSchema\PostTags;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-tags', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
