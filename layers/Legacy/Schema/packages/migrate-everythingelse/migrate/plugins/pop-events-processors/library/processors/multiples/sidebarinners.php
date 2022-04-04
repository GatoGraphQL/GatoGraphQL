<?php

class GD_EM_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS = 'multiple-sidebarinner-section-events';
    public final const MODULE_MULTIPLE_SIDEBARINNER_SECTION_PASTEVENTS = 'multiple-sidebarinner-section-pastevents';
    public final const MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS_CALENDAR = 'multiple-sidebarinner-section-events-calendar';
    public final const MODULE_MULTIPLE_SIDEBARINNER_TAG_EVENTS = 'multiple-sidebarinner-tag-events';
    public final const MODULE_MULTIPLE_SIDEBARINNER_TAG_PASTEVENTS = 'multiple-sidebarinner-tag-pastevents';
    public final const MODULE_MULTIPLE_SIDEBARINNER_TAG_EVENTS_CALENDAR = 'multiple-sidebarinner-tag-events-calendar';
    public final const MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTS = 'multiple-sidebarinner-section-authorevents';
    public final const MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS = 'multiple-sidebarinner-section-authorpastevents';
    public final const MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR = 'multiple-sidebarinner-section-authoreventscalendar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS],
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_PASTEVENTS],
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS_CALENDAR],
            
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_TAG_EVENTS],
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_TAG_PASTEVENTS],
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_TAG_EVENTS_CALENDAR],
            
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTS],
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS],
            [self::class, self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS:
            case self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_PASTEVENTS:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_SECTIONWITHMAP];
                $ret[] = [PoP_Events_Module_Processor_CustomDelegatorFilters::class, PoP_Events_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_EVENTS];
                break;

            case self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS_CALENDAR:
                $ret[] = [GD_Custom_EM_Module_Processor_ButtonGroups::class, GD_Custom_EM_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_CALENDARSECTION];
                $ret[] = [PoP_Events_Module_Processor_CustomDelegatorFilters::class, PoP_Events_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_EVENTSCALENDAR];
                break;

            case self::MODULE_MULTIPLE_SIDEBARINNER_TAG_EVENTS:
            case self::MODULE_MULTIPLE_SIDEBARINNER_TAG_PASTEVENTS:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_TAGSECTIONWITHMAP];
                $ret[] = [PoP_Events_Module_Processor_CustomDelegatorFilters::class, PoP_Events_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_TAGEVENTS];
                break;

            case self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS_CALENDAR:
                $ret[] = [GD_Custom_EM_Module_Processor_ButtonGroups::class, GD_Custom_EM_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_TAGCALENDARSECTION];
                $ret[] = [PoP_Events_Module_Processor_CustomDelegatorFilters::class, PoP_Events_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_TAGEVENTSCALENDAR];
                break;

            case self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTS:
            case self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS:
                $ret[] = [GD_Custom_Module_Processor_ButtonGroups::class, GD_Custom_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_AUTHORSECTIONWITHMAP];
                $ret[] = [PoP_Events_Module_Processor_CustomDelegatorFilters::class, PoP_Events_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_AUTHOREVENTS];
                break;

            case self::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR:
                $ret[] = [GD_Custom_EM_Module_Processor_ButtonGroups::class, GD_Custom_EM_Module_Processor_ButtonGroups::MODULE_BUTTONGROUP_AUTHORCALENDARSECTION];
                $ret[] = [PoP_Events_Module_Processor_CustomDelegatorFilters::class, PoP_Events_Module_Processor_CustomDelegatorFilters::MODULE_DELEGATORFILTER_AUTHOREVENTS];
                break;
        }
        
        return $ret;
    }
}



