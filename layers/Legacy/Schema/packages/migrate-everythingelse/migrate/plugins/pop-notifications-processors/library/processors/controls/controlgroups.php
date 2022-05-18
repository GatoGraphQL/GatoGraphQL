<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class AAL_PoPProcessors_Module_Processor_ControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_AAL_CONTROLGROUP_NOTIFICATIONLIST = 'controlgroup-notificationlist';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_AAL_CONTROLGROUP_NOTIFICATIONLIST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_AAL_CONTROLGROUP_NOTIFICATIONLIST:
                $ret[] = [AAL_PoPProcessors_Module_Processor_ControlButtonGroups::class, AAL_PoPProcessors_Module_Processor_ControlButtonGroups::COMPONENT_AAL_CONTROLBUTTONGROUP_NOTIFICATIONS_MARKALLASREAD];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_LOADLATESTBLOCK];
                break;
        }

        return $ret;
    }
}


