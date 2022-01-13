<?php

class PoP_SocialNetwork_Module_Processor_UserMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK = 'multicomponent-emailnotifications-network';
    public const MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC = 'multicomponent-emailnotifications-subscribedtopic';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK],
            [self::class, self::MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK:
                $ret[] = [PoP_SocialNetwork_Module_Processor_UserCodes::class, PoP_SocialNetwork_Module_Processor_UserCodes::MODULE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL];

                // Allow URE to hook in the "Joins community" input
                if ($forminputs = \PoP\Root\App::getHookManager()->applyFilters(
                    'PoP_Module_Processor_UserMultipleComponents:emailnotifications:network:modules',
                    array(
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT],
                        [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST],
                    )
                )
                ) {
                    $ret = array_merge(
                        $ret,
                        $forminputs
                    );
                }
                break;

            case self::MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC:
                $ret[] = [PoP_SocialNetwork_Module_Processor_UserCodes::class, PoP_SocialNetwork_Module_Processor_UserCodes::MODULE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL];
                $ret[] = [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT];
                $ret[] = [PoP_SocialNetwork_Module_Processor_EmailFormGroups::class, PoP_SocialNetwork_Module_Processor_EmailFormGroups::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT];
                break;
        }

        return $ret;
    }
}



