<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafModuleField;

class GD_URE_AAL_CustomMultipleLayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_PreviewNotificationLayoutsBase:getConditionalBottomSubmodules',
            array($this, 'getConditionalBottomSubmodules')
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_MultipleComponentLayouts:modules',
            array($this, 'getQuicklinkgroupBottomSubmodule')
        );
    }

    /**
     * @param ConditionalLeafModuleField[] $conditionalLeafModuleFields
     * @return ConditionalLeafModuleField[]
     */
    public function getConditionalBottomSubmodules(array $conditionalLeafModuleFields): array
    {
        // Add layout for action "updated_user_membership"
        $conditionalLeafModuleFields[] = new ConditionalLeafModuleField(
            'isUserNotification',
            [
                [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::MODULE_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP],
            ]
        );
        return $conditionalLeafModuleFields;
    }

    /**
     * @param ConditionalLeafModuleField[] $conditionalLeafModuleFields
     * @return ConditionalLeafModuleField[]
     */
    public function getQuicklinkgroupBottomSubmodule(array $conditionalLeafModuleFields): array
    {
        // Add layout for action "joined_community"
        $conditionalLeafModuleFields[] = new ConditionalLeafModuleField(
            'isUserNotification',
            [
                [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::MODULE_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY]
            ]
        );
        return $conditionalLeafModuleFields;
    }
}

/**
 * Initialization
 */
new GD_URE_AAL_CustomMultipleLayoutHooks();
