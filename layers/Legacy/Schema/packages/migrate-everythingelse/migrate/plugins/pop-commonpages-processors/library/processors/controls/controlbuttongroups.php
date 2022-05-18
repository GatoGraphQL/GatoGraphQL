<?php

class GD_CommonPages_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ = 'customcontrolbuttongroup-addcontentfaq';
    public final const MODULE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ = 'customcontrolbuttongroup-accountfaq';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ],
            [self::class, self::COMPONENT_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_CustomAnchorControls::class, GD_CommonPages_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ];
                break;

            case self::COMPONENT_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ:
                $ret[] = [GD_CommonPages_Module_Processor_CustomAnchorControls::class, GD_CommonPages_Module_Processor_CustomAnchorControls::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ];
                break;
        }
        
        return $ret;
    }
}


