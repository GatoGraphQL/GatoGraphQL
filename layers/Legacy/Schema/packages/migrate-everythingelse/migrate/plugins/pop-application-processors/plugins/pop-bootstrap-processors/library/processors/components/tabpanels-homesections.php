<?php

class PoP_Module_Processor_HomeSectionTabPanelComponents extends PoP_Module_Processor_HomeSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_HOMECONTENT = 'tabpanel-homecontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_HOMECONTENT],
        );
    }

    public function getPanelSubcomponents(array $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_HOMECONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_HOMECONTENT:
                return [
                    [
                        'header-subcomponent' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-subcomponent' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_LIST],
                        'subheader-subcomponents' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HOMECONTENT_SCROLL_LIST],
                        ],
                    ],
                ];
        }

        return parent::getPanelHeaders($component, $props);
    }
}


