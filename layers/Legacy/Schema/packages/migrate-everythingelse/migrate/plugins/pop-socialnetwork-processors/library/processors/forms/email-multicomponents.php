<?php

class PoP_SocialNetwork_Module_Processor_UserMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK = 'multicomponent-emailnotifications-network';
    public final const MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC = 'multicomponent-emailnotifications-subscribedtopic';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK],
            [self::class, self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK:
                $ret[] = [PoP_SocialNetwork_Module_Processor_UserCodes::class, PoP_SocialNetwork_Module_Processor_UserCodes::COMPONENT_CODE_EMAILNOTIFICATIONS_NETWORKLABEL];

                // Allow URE to hook in the "Joins community" input
                if ($forminputs = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_UserMultipleComponents:emailnotifications:network:modules',
                    array(
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST],
                    )
                )
                ) {
                    $ret = array_merge(
                        $ret,
                        $forminputs
                    );
                }
                break;

            case self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC:
                $ret[] = [PoP_SocialNetwork_Module_Processor_UserCodes::class, PoP_SocialNetwork_Module_Processor_UserCodes::COMPONENT_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL];
                $ret[] = [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT];
                $ret[] = [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT];
                break;
        }

        return $ret;
    }
}



