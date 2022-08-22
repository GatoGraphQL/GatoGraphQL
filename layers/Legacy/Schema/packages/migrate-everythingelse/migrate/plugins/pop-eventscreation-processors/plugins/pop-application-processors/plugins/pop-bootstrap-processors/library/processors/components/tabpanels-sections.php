<?php

class PoP_EventsCreation_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_MYEVENTS = 'tabpanel-myevents';
    public final const COMPONENT_TABPANEL_MYPASTEVENTS = 'tabpanel-mypastevents';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABPANEL_MYEVENTS,
            self::COMPONENT_TABPANEL_MYPASTEVENTS,
        );
    }

    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_MYEVENTS:
            case self::COMPONENT_TABPANEL_MYPASTEVENTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_TABPANEL_MYEVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_TABLE_EDIT],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_MYPASTEVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_MYEVENTS:
                return array(
                    [
                        'header-subcomponent' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_TABLE_EDIT],
                    ],
                    [
                        'header-subcomponent' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-subcomponents' =>  array(
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_MYPASTEVENTS:
                return array(
                    [
                        'header-subcomponent' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
                    ],
                    [
                        'header-subcomponent' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-subcomponents' =>  array(
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}


