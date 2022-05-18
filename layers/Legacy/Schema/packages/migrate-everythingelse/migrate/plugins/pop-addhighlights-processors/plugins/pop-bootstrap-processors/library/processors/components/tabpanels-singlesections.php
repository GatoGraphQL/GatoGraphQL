<?php

class PoP_AddHighlights_Module_Processor_SingleSectionTabPanelComponents extends PoP_Module_Processor_SingleSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT = 'tabpanel-singlerelatedhighlightcontent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT],
        );
    }

    protected function getDefaultActivepanelFormat(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEHIGHLIGHTS);
        }

        return parent::getDefaultActivepanelFormat($componentVariation);
    }

    public function getPanelSubmodules(array $componentVariation)
    {
        $ret = parent::getPanelSubmodules($componentVariation);

        switch ($componentVariation[1]) {
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

    public function getPanelHeaders(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
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

        return parent::getPanelHeaders($componentVariation, $props);
    }
}


