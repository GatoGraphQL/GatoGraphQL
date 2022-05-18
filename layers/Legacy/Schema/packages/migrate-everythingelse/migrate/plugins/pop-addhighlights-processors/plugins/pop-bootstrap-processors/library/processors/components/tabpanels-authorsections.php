<?php

class PoP_AddHighlights_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_AUTHORHIGHLIGHTS = 'tabpanel-authorhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_AUTHORHIGHLIGHTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORHIGHLIGHTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_HIGHLIGHTS);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORHIGHLIGHTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
                        // [self::class, self::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_DETAILS],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORHIGHLIGHTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
                            [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($component, $props);
    }
}


