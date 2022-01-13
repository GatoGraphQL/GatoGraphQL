<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ServiceWorkers_UserState_Job_Fetch_Hooks
{
    public function __construct()
    {
        $resourceType = 'json';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_ServiceWorkers_Job_Fetch:exclude-paramvalues:'.$resourceType,
            array($this, 'getExcludedParamvalues')
        );
    }

    public function getExcludedParamvalues($excluded)
    {
        $excluded[] = array(
            \PoP\ComponentModel\Constants\Params::ACTIONS,
            POP_ACTION_LOADUSERSTATE
        );
        return $excluded;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_UserState_Job_Fetch_Hooks();
