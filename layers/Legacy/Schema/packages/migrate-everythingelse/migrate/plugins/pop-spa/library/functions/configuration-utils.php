<?php
use PoP\Hooks\Facades\HooksAPIFacade;

define('POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD', 'popwebplatform-backgroundload');

class PoP_SPA_ConfigurationUtils
{
    public static function getBackgroundloadRouteConfigurations()
    {

        // Return a list of items like this:
        // $route => array('preload' => bool value, 'targets' => array value)
        return HooksAPIFacade::getInstance()->applyFilters(POP_HOOK_POPWEBPLATFORM_BACKGROUNDLOAD, array());
    }
}
