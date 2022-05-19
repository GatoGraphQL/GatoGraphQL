<?php

class PoP_ContentCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const COMPONENT_BLOCK_MYCONTENT_TABLE_EDIT = 'block-mycontent-table-edit';
    public final const COMPONENT_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW = 'block-mycontent-scroll-simpleviewpreview';
    public final const COMPONENT_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW = 'block-mycontent-scroll-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_MYCONTENT_TABLE_EDIT],
            [self::class, self::COMPONENT_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            self::COMPONENT_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            self::COMPONENT_BLOCK_MYCONTENT_TABLE_EDIT => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_MYCONTENT_TABLE_EDIT => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_TABLE_EDIT],
            self::COMPONENT_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
            self::COMPONENT_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getSectionfilterModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_MYCONTENT_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy() && PoP_ApplicationProcessors_Utils::addSections()) {
                    return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::COMPONENT_INSTANTANEOUSFILTER_CONTENTSECTIONS];
                }
                break;
        }

        return parent::getSectionfilterModule($component);
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_MYCONTENT_TABLE_EDIT:
            case self::COMPONENT_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }
}



