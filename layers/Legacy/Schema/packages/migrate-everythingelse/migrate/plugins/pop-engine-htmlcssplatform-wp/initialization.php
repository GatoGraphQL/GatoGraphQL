<?php
namespace PoP\EngineHTMLCSSPlatform;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-engine-htmlcssplatform-wp', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';

        /**
         * Load the Library
         */
        include_once 'library/load.php';
    }
}
