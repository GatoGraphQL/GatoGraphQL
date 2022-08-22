<?php

class PoP_ContentPostLinks_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_AUTHORLINKS = 'tabpanel-authorlinks';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABPANEL_AUTHORLINKS,
        );
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_TABPANEL_AUTHORLINKS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_AUTHORLINKS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_SIMPLEVIEW],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_DETAILS],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_THUMBNAIL],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORLINKS_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($component, $props);
    }
}


