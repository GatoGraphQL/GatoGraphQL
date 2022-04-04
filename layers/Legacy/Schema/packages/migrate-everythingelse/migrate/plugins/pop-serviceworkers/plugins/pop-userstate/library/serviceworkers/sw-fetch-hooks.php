<?php

class PoP_ServiceWorkers_UserState_Job_Fetch_Hooks
{
    public function __construct()
    {
        $resourceType = 'json';
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_Fetch:exclude-paramvalues:'.$resourceType,
            $this->getExcludedParamvalues(...)
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
