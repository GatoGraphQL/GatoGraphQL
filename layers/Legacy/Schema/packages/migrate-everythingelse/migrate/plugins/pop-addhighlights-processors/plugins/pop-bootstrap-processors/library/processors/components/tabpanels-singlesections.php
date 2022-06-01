<?php

class PoP_AddHighlights_Module_Processor_SingleSectionTabPanelComponents extends PoP_Module_Processor_SingleSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT = 'tabpanel-singlerelatedhighlightcontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT],
        );
    }

    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEHIGHLIGHTS);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
                        [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
                    ],
                    [
                        'header-subcomponent' => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($component, $props);
    }
}


