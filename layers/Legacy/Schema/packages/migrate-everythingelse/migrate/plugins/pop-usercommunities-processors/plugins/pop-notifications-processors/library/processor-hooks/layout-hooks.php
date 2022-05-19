<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentField;

class GD_URE_AAL_CustomMultipleLayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_PreviewNotificationLayoutsBase:getConditionalBottomSubcomponents',
            $this->getConditionalBottomSubcomponents(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_MultipleComponentLayouts:modules',
            $this->getQuicklinkgroupBottomSubcomponent(...)
        );
    }

    /**
     * @param ConditionalLeafComponentField[] $conditionalLeafComponentFields
     * @return ConditionalLeafComponentField[]
     */
    public function getConditionalBottomSubcomponents(array $conditionalLeafComponentFields): array
    {
        // Add layout for action "updated_user_membership"
        $conditionalLeafComponentFields[] = new ConditionalLeafComponentField(
            'isUserNotification',
            [
                [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP],
            ]
        );
        return $conditionalLeafComponentFields;
    }

    /**
     * @param ConditionalLeafComponentField[] $conditionalLeafComponentFields
     * @return ConditionalLeafComponentField[]
     */
    public function getQuicklinkgroupBottomSubcomponent(array $conditionalLeafComponentFields): array
    {
        // Add layout for action "joined_community"
        $conditionalLeafComponentFields[] = new ConditionalLeafComponentField(
            'isUserNotification',
            [
                [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY]
            ]
        );
        return $conditionalLeafComponentFields;
    }
}

/**
 * Initialization
 */
new GD_URE_AAL_CustomMultipleLayoutHooks();
