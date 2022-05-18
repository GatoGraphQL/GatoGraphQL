<?php

class GD_EM_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_EVENTS = 'tabpanel-events';
    public final const MODULE_TABPANEL_PASTEVENTS = 'tabpanel-pastevents';
    public final const MODULE_TABPANEL_EVENTSCALENDAR = 'tabpanel-eventscalendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_EVENTS],
            [self::class, self::MODULE_TABPANEL_PASTEVENTS],
            [self::class, self::MODULE_TABPANEL_EVENTSCALENDAR],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_TABPANEL_EVENTSCALENDAR:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTIONCALENDAR);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_TABPANEL_EVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_LIST],
                        [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLLMAP],
                    )
                );
                break;

            case self::MODULE_TABPANEL_PASTEVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST],
                        [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP],
                    )
                );
                break;

            case self::MODULE_TABPANEL_EVENTSCALENDAR:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTSCALENDAR_CALENDAR],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTSCALENDAR_CALENDARMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_TABPANEL_EVENTS:
                return array(
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_SIMPLEVIEW],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLLMAP],
                    ],
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_DETAILS],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_THUMBNAIL],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_EVENTS_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_PASTEVENTS:
                return array(
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_SIMPLEVIEW],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_DETAILS],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_THUMBNAIL],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP],
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}


