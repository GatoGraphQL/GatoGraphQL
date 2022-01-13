<?php

class PoP_NotificationsProcessors_MyPreferencesHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_UserMultipleComponents:emaildigests:modules',
            array($this, 'getEmaildigestsForminputgroups')
        );
    }

    public function getEmaildigestsForminputgroups($modules)
    {
        array_splice(
            $modules, 
            array_search(
                [PoP_Module_Processor_UserCodes::class, PoP_Module_Processor_UserCodes::MODULE_CODE_EMAILDIGESTS_LABEL], 
                $modules
            )+1, 
            0, 
            array(
                [PoP_Notifications_Module_Processor_EmailFormGroups::class, PoP_Notifications_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILDIGESTS_DAILYNOTIFICATIONS],
            )
        );
        return $modules;
    }
}


/**
 * Initialization
 */
new PoP_NotificationsProcessors_MyPreferencesHooks();
