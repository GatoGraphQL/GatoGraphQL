<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class CommonPages_EM_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CONTROLGROUP_MYLOCATIONPOSTLIST = 'controlgroup-mylocationpostlist';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLGROUP_MYLOCATIONPOSTLIST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::MODULE_CONTROLGROUP_MYLOCATIONPOSTLIST:
                $addposts = array(
                    self::MODULE_CONTROLGROUP_MYLOCATIONPOSTLIST => [CommonPages_EM_Module_Processor_ControlButtonGroups::class, CommonPages_EM_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_ADDLOCATIONPOST],
                );
                $ret[] = $addposts[$component[1]];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;
        }

        return $ret;
    }
}


