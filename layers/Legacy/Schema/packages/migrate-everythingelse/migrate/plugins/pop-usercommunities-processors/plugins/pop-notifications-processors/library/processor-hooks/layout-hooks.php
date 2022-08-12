<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

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
     * @param ConditionalLeafComponentFieldNode[] $conditionalLeafComponentFieldNodes
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getConditionalBottomSubcomponents(array $conditionalLeafComponentFieldNodes): array
    {
        // Add layout for action "updated_user_membership"
        $conditionalLeafComponentFieldNodes[] = new ConditionalLeafComponentFieldNode(
            new LeafField(
                'isUserNotification',
                null,
                [],
                [],
                ASTNodesFactory::getNonSpecificLocation()
            ),
            [
                [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::COMPONENT_UREAAL_MULTICOMPONENTACTIONWRAPPER_LAYOUTUSER_MEMBERSHIP],
            ]
        );
        return $conditionalLeafComponentFieldNodes;
    }

    /**
     * @param ConditionalLeafComponentFieldNode[] $conditionalLeafComponentFieldNodes
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getQuicklinkgroupBottomSubcomponent(array $conditionalLeafComponentFieldNodes): array
    {
        // Add layout for action "joined_community"
        $conditionalLeafComponentFieldNodes[] = new ConditionalLeafComponentFieldNode(
            new LeafField(
                'isUserNotification',
                null,
                [],
                [],
                ASTNodesFactory::getNonSpecificLocation()
            ),
            [
                [Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::class, Wassup_URE_AAL_Module_Processor_MultiMembershipWrappers::COMPONENT_UREAAL_QUICKLINKGROUPACTIONWRAPPER_USER_JOINEDCOMMUNITY]
            ]
        );
        return $conditionalLeafComponentFieldNodes;
    }
}

/**
 * Initialization
 */
new GD_URE_AAL_CustomMultipleLayoutHooks();
