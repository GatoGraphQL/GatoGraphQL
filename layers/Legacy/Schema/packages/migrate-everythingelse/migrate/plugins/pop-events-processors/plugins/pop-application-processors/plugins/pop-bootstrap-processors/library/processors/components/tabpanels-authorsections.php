<?php

class GD_EM_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_AUTHOREVENTS = 'tabpanel-authorevents';
    public final const MODULE_TABPANEL_AUTHORPASTEVENTS = 'tabpanel-authorpastevents';
    public final const MODULE_TABPANEL_AUTHOREVENTSCALENDAR = 'tabpanel-authoreventscalendar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_AUTHOREVENTS],
            [self::class, self::COMPONENT_TABPANEL_AUTHORPASTEVENTS],
            [self::class, self::COMPONENT_TABPANEL_AUTHOREVENTSCALENDAR],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHOREVENTSCALENDAR:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTIONCALENDAR);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHOREVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_LIST],
                        [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLLMAP],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORPASTEVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST],
                        [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHOREVENTSCALENDAR:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTSCALENDAR_CALENDAR],
                        [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTSCALENDAR_CALENDARMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHOREVENTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_SIMPLEVIEW],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLLMAP],
                    ],
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_DETAILS],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_THUMBNAIL],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORPASTEVENTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP],
                    ],
                    [
                        'header-submodule' => [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_DETAILS],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_THUMBNAIL],
                            [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


