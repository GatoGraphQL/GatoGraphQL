<?php

class GD_Custom_EM_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const COMPONENT_BUTTONGROUP_CALENDARSECTION = 'buttongroup-calendarsection';
    public final const COMPONENT_BUTTONGROUP_TAGCALENDARSECTION = 'buttongroup-tagcalendarsection';
    public final const COMPONENT_BUTTONGROUP_AUTHORCALENDARSECTION = 'buttongroup-authorcalendarsection';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONGROUP_CALENDARSECTION],
            [self::class, self::COMPONENT_BUTTONGROUP_TAGCALENDARSECTION],
            [self::class, self::COMPONENT_BUTTONGROUP_AUTHORCALENDARSECTION],
        );
    }

    protected function getHeadersdataScreen(array $component, array &$props)
    {
        switch ($component[1]) {
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



