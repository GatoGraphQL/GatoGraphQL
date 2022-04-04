<?php

class PoP_AddHighlights_Module_Processor_SingleSectionTabPanelComponents extends PoP_Module_Processor_SingleSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT = 'tabpanel-singlerelatedhighlightcontent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEHIGHLIGHTS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
                $ret = array(
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
                    ],
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($module, $props);
    }
}


