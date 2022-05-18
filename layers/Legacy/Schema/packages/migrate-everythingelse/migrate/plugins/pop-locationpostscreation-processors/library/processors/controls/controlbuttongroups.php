<?php

class CommonPages_EM_Module_Processor_ControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CONTROLBUTTONGROUP_ADDLOCATIONPOST = 'customcontrolbuttongroup-addlocationpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLBUTTONGROUP_ADDLOCATIONPOST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CONTROLBUTTONGROUP_ADDLOCATIONPOST:
                $ret[] = [CommonPagesEM_Module_Processor_AnchorControls::class, CommonPagesEM_Module_Processor_AnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDLOCATIONPOST];
                $ret = \PoP\Root\App::applyFilters(
                    'CommonPages_EM_Module_Processor_ControlButtonGroups:modules',
                    $ret,
                    $component,
                    $props
                );
                break;
        }
        
        return $ret;
    }
}


