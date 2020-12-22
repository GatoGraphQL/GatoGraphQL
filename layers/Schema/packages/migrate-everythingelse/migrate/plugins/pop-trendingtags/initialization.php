<?php
namespace PoP\TrendingTags;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-trendingtags', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Load the PoP Library
         */
        include_once 'library/load.php';
    }
}
