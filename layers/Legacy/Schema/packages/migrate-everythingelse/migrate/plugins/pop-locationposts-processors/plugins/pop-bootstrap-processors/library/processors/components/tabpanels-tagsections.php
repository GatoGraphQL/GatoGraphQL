<?php

class PoP_LocationPosts_Module_Processor_TagSectionTabPanelComponents extends PoP_Module_Processor_TagSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_TAGLOCATIONPOSTS = 'tabpanel-taglocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_TAGLOCATIONPOSTS],
        );
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_TAGLOCATIONPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
                        [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
                        [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_TAGLOCATIONPOSTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP],
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


