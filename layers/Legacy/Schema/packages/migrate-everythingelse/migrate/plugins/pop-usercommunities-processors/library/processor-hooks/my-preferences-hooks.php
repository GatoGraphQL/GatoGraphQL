<?php

class PoP_UserCommunitiesProcessors_MyPreferencesHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_UserMultipleComponents:emailnotifications:network:modules',
            $this->getForminputgroups(...)
        );
    }

    public function getForminputgroups($components)
    {
        return array_merge(
            $components,
            array(
                [PoP_UserCommunitiesProcessors_Module_Processor_EmailFormGroups::class, PoP_UserCommunitiesProcessors_Module_Processor_EmailFormGroups::COMPONENT_URE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY],
            )
        );
    }
}


/**
 * Initialization
 */
new PoP_UserCommunitiesProcessors_MyPreferencesHooks();
