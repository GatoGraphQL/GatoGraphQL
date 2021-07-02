<?php

class PoP_EventsCreation_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public const MODULE_TABPANEL_MYEVENTS = 'tabpanel-myevents';
    public const MODULE_TABPANEL_MYPASTEVENTS = 'tabpanel-mypastevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_MYEVENTS],
            [self::class, self::MODULE_TABPANEL_MYPASTEVENTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYEVENTS:
            case self::MODULE_TABPANEL_MYPASTEVENTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYEVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYPASTEVENTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYEVENTS:
                return array(
                    [
                        'header-submodule' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYEVENTS_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYEVENTS_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYPASTEVENTS:
                return array(
                    [
                        'header-submodule' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPASTEVENTS_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_EventsCreation_Module_Processor_MySectionDataloads::class, PoP_EventsCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );
        }

        return parent::getPanelHeaders($module, $props);
    }
}


