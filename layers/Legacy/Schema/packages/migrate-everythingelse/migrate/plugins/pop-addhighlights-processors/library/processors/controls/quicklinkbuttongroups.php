<?php

class PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT = 'quicklinkbuttongroup-highlightedit';
    public final const COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW = 'quicklinkbuttongroup-highlightview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT,
            self::COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT:
                $ret[] = [PoP_AddHighlights_Module_Processor_Buttons::class, PoP_AddHighlights_Module_Processor_Buttons::COMPONENT_BUTTON_HIGHLIGHTEDIT];
                break;

            case self::COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW:
                $ret[] = [Wassup_Module_Processor_ButtonWrappers::class, Wassup_Module_Processor_ButtonWrappers::COMPONENT_BUTTONWRAPPER_HIGHLIGHTVIEW];
                break;
        }
        
        return $ret;
    }
}


