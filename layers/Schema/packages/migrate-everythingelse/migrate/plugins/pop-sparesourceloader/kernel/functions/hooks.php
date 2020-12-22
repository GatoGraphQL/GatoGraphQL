<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_SPAResourceLoader_Hooks
{
    public function __construct()
    {

        // Do not load the handlebars helpers from ResourceLoader, since the one from SPAResourceLoader will take over it
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ResourceLoader_Utils:registerHandlebarshelperScript',
            '__return_false'
        );
    }
}

/**
 * Initialization
 */
new PoP_SPAResourceLoader_Hooks();
