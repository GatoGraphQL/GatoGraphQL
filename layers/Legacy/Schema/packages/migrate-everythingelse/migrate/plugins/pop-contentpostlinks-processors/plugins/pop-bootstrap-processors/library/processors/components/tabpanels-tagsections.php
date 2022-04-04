<?php

class PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelComponents extends PoP_Module_Processor_TagSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_TAGLINKS = 'tabpanel-taglinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_TAGLINKS],
        );
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_TAGLINKS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
                        [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_TAGLINKS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_SIMPLEVIEW],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_DETAILS],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_THUMBNAIL],
                            [PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::class, PoP_ContentPostLinks_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_TAGLINKS_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($module, $props);
    }
}


