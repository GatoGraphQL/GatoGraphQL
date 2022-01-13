<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserCommunitiesProcessors_MyPreferencesHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_UserMultipleComponents:emailnotifications:network:modules',
            array($this, 'getForminputgroups')
        );
    }

    public function getForminputgroups($modules)
    {
        return array_merge(
            $modules,
            array(
                [PoP_UserCommunitiesProcessors_Module_Processor_EmailFormGroups::class, PoP_UserCommunitiesProcessors_Module_Processor_EmailFormGroups::MODULE_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
            )
        );
    }
}


/**
 * Initialization
 */
new PoP_UserCommunitiesProcessors_MyPreferencesHooks();
