<?php

class GD_CommonPages_Module_Processor_CustomControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ = 'customcontrolbuttongroup-addcontentfaq';
    public final const COMPONENT_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ = 'customcontrolbuttongroup-accountfaq';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ,
            self::COMPONENT_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
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


