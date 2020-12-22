<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPHTMLCSSPlatform_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-engine-htmlcssplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Kernel
         */
        require_once 'kernel/load.php';

        /**
         * Load the Library first
         */
        require_once 'library/load.php';
    }
}

/**
 * Initialization
 */
global $PoPHTMLCSSPlatform_Initialization;
$PoPHTMLCSSPlatform_Initialization = new PoPHTMLCSSPlatform_Initialization();
