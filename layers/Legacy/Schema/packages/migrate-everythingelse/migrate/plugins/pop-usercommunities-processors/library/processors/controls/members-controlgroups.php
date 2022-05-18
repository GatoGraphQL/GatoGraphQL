<?php

class GD_URE_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CONTROLGROUP_MYMEMBERS = 'controlgroup-mymembers';
    public final const MODULE_CONTROLGROUP_MYBLOCKMEMBERS = 'controlgroup-myblockmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLGROUP_MYMEMBERS],
            [self::class, self::COMPONENT_CONTROLGROUP_MYBLOCKMEMBERS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTROLGROUP_MYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomControlButtonGroups::class, GD_URE_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::COMPONENT_CONTROLGROUP_MYBLOCKMEMBERS:
                $ret[] = [GD_URE_Module_Processor_CustomControlButtonGroups::class, GD_URE_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_INVITENEWMEMBERS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;
        }

        return $ret;
    }
}


