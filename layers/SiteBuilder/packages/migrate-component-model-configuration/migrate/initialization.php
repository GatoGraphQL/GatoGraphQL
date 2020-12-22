<?php
namespace PoP\ConfigurationComponentModel;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-component-model-configuration', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        require_once 'library/load.php';
    }
}

/**
 * Initialization
 */
new Initialization();
