<?php

class PoP_AddHighlights_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_HIGHLIGHTS = 'tabpanel-highlights';
    public final const MODULE_TABPANEL_MYHIGHLIGHTS = 'tabpanel-myhighlights';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_HIGHLIGHTS],
            [self::class, self::MODULE_TABPANEL_MYHIGHLIGHTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_HIGHLIGHTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_HIGHLIGHTS);

            case self::MODULE_TABPANEL_MYHIGHLIGHTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYHIGHLIGHTS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_HIGHLIGHTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW],
                        // [self::class, self::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_DETAILS],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_HIGHLIGHTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
                            [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($module, $props);
    }
}


