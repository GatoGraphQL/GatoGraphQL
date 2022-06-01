<?php

class Wassup_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT = 'quicklinkbuttongroup-addonspostedit';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT = 'quicklinkbuttongroup-addonsormainpostedit';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT,
            self::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT:
                $ret[] = [Wassup_Module_Processor_Buttons::class, Wassup_Module_Processor_Buttons::COMPONENT_BUTTON_ADDONSPOSTEDIT];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT:
                $ret[] = [Wassup_Module_Processor_Buttons::class, Wassup_Module_Processor_Buttons::COMPONENT_BUTTON_ADDONSORMAINPOSTEDIT];
                break;
        }
        
        return $ret;
    }
}


