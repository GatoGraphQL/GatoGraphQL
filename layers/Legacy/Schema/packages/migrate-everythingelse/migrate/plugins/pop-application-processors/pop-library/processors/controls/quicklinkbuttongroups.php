<?php

class Wassup_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT = 'quicklinkbuttongroup-addonspostedit';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT = 'quicklinkbuttongroup-addonsormainpostedit';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT],
            [self::class, self::COMPONENT_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);
    
        switch ($component[1]) {
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


