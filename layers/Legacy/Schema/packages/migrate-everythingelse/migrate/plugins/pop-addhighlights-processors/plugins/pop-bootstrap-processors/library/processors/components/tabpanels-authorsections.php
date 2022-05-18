<?php

class PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_AUTHORHIGHLIGHTS = 'tabpanel-authorhighlights';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_AUTHORHIGHLIGHTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORHIGHLIGHTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_HIGHLIGHTS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORHIGHLIGHTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
                        // [self::class, self::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_DETAILS],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORHIGHLIGHTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
                            [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($module, $props);
    }
}


