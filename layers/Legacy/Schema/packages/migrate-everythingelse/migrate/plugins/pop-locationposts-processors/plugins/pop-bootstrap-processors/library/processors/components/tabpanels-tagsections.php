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

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
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

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_TAGLOCATIONPOSTS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_DETAILS],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
                            [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-subcomponent' => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP],
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


