<?php

class PoP_LocationPosts_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_LOCATIONPOSTS = 'tabpanel-locationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_LOCATIONPOSTS],
        );
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_TABPANEL_LOCATIONPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],
                        [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLLMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_TABPANEL_LOCATIONPOSTS:
                return array(
                    [
                        'header-submodule' => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_DETAILS],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_THUMBNAIL],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLLMAP],
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}


