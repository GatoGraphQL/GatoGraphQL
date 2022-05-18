<?php

class PoP_ContentPostLinks_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_LINKS = 'tabpanel-links';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_LINKS],
        );
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_TABPANEL_LINKS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_DETAILS],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_TABPANEL_LINKS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_SIMPLEVIEW],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_DETAILS],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_THUMBNAIL],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LINKS_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($component, $props);
    }
}


