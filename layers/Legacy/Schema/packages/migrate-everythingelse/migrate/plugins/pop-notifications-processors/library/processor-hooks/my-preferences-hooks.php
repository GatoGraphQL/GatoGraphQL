<?php

class PoP_NotificationsProcessors_MyPreferencesHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_UserMultipleComponents:emaildigests:modules',
            $this->getEmaildigestsForminputgroups(...)
        );
    }

    public function getEmaildigestsForminputgroups($componentVariations)
    {
        array_splice(
            $componentVariations, 
            array_search(
                [PoP_Module_Processor_UserCodes::class, PoP_Module_Processor_UserCodes::MODULE_CODE_EMAILDIGESTS_LABEL], 
                $componentVariations
            )+1, 
            0, 
            array(
                [PoP_Notifications_Module_Processor_EmailFormGroups::class, PoP_Notifications_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS],
            )
        );
        return $componentVariations;
    }
}


/**
 * Initialization
 */
new PoP_NotificationsProcessors_MyPreferencesHooks();
