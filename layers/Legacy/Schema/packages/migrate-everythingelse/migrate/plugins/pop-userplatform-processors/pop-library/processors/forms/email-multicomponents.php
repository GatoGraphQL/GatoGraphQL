<?php

class PoP_Module_Processor_UserMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS = 'multicomponent-emailnotifications';
    public final const COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL = 'multicomponent-emailnotifications-general';
    public final const COMPONENT_MULTICOMPONENT_EMAILDIGESTS = 'multicomponent-emaildigests';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS,
            self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL,
            self::COMPONENT_MULTICOMPONENT_EMAILDIGESTS,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS:
                $ret[] = [PoP_Module_Processor_UserCodes::class, PoP_Module_Processor_UserCodes::COMPONENT_CODE_EMAILNOTIFICATIONS_LABEL];
                $ret[] = self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL;
                // Allow PoP Social Network to hook in its modules
                if ($forminputs = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_UserMultipleComponents:emailnotifications:modules',
                    array()
                )) {
                    $ret = array_merge(
                        $ret,
                        $forminputs
                    );
                }
                break;

            case self::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_GENERAL:
                $ret[] = [PoP_Module_Processor_UserCodes::class, PoP_Module_Processor_UserCodes::COMPONENT_CODE_EMAILNOTIFICATIONS_GENERALLABEL];
                $ret[] = [PoP_Module_Processor_EmailFormGroups::class, PoP_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_GENERAL_NEWPOST];
                break;

            case self::COMPONENT_MULTICOMPONENT_EMAILDIGESTS:
                $forminputs = array(
                    [PoP_Module_Processor_UserCodes::class, PoP_Module_Processor_UserCodes::COMPONENT_CODE_EMAILDIGESTS_LABEL],
                    [PoP_Module_Processor_EmailFormGroups::class, PoP_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_WEEKLYLATESTPOSTS],
                    [PoP_Module_Processor_EmailFormGroups::class, PoP_Module_Processor_EmailFormGroups::COMPONENT_FORMINPUTGROUP_EMAILDIGESTS_SPECIALPOSTS],
                );

                // Allow PoP Social Network to hook in its modules
                $forminputs = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_UserMultipleComponents:emaildigests:modules',
                    $forminputs
                );
                $ret = array_merge(
                    $ret,
                    $forminputs
                );
                break;
        }

        return $ret;
    }
}



