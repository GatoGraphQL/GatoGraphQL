<?php
class PoP_NewsletterProcessors_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-newsletter-processors', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Library
         */
        include_once 'library/load.php';

        /**
         * Load the Plugins Library
         */
        include_once 'plugins/load.php';
    }
}
