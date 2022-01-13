<?php

class GD_URE_AAL_CustomMultipleLayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_PreviewNotificationLayoutsBase:getConditionalOnDataFieldSubmodules',
            array($this, 'getConditionalOnDataFieldSubmodules'),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_MultipleComponentLayouts:modules',
            array($this, 'getQuicklinkgroupBottomSubmodule')
        );
    }

    public function getConditionalOnDataFieldSubmodules($submodules, $module)
    {
        // Add layout for action "updated_user_membership"
        $submodules['isUserNotification'] = [
            [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::MODULE_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP],
        ];
        return $submodules;
    }

    public function getQuicklinkgroupBottomSubmodule($submodules)
    {
        // Add layout for action "joined_community"
        $submodules['isUserNotification'] = [
            [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::MODULE_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY]
        ];
        return $submodules;
    }
}

/**
 * Initialization
 */
new GD_URE_AAL_CustomMultipleLayoutHooks();
