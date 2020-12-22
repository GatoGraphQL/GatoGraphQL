<?php
use PoP\Hooks\Facades\HooksAPIFacade;

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
            GD_URLPARAM_ACTIONS,
            POP_ACTION_LOADUSERSTATE
        );
        return $excluded;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_UserState_Job_Fetch_Hooks();
