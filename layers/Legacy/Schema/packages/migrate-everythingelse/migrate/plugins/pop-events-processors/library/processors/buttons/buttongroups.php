<?php

class GD_Custom_EM_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const COMPONENT_BUTTONGROUP_CALENDARSECTION = 'buttongroup-calendarsection';
    public final const COMPONENT_BUTTONGROUP_TAGCALENDARSECTION = 'buttongroup-tagcalendarsection';
    public final const COMPONENT_BUTTONGROUP_AUTHORCALENDARSECTION = 'buttongroup-authorcalendarsection';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONGROUP_CALENDARSECTION,
            self::COMPONENT_BUTTONGROUP_TAGCALENDARSECTION,
            self::COMPONENT_BUTTONGROUP_AUTHORCALENDARSECTION,
        );
    }

    protected function getHeadersdataScreen(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONGROUP_CALENDARSECTION:
                return POP_SCREEN_SECTIONCALENDAR;
            
            case self::COMPONENT_BUTTONGROUP_TAGCALENDARSECTION:
                return POP_SCREEN_TAGSECTIONCALENDAR;

            case self::COMPONENT_BUTTONGROUP_AUTHORCALENDARSECTION:
                return POP_SCREEN_AUTHORSECTIONCALENDAR;
        }

        return parent::getHeadersdataScreen($component, $props);
    }
}



