<?php

class PoP_ContentCreation_Module_Processor_MySectionBlocks extends PoP_Module_Processor_MySectionBlocksBase
{
    public final const MODULE_BLOCK_MYCONTENT_TABLE_EDIT = 'block-mycontent-table-edit';
    public final const MODULE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW = 'block-mycontent-scroll-simpleviewpreview';
    public final const MODULE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW = 'block-mycontent-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYCONTENT_TABLE_EDIT],
            [self::class, self::MODULE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            self::MODULE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            self::MODULE_BLOCK_MYCONTENT_TABLE_EDIT => POP_CONTENTCREATION_ROUTE_MYCONTENT,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_MYCONTENT_TABLE_EDIT => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCONTENT_TABLE_EDIT],
            self::MODULE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
            self::MODULE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    protected function getSectionfilterModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_MYCONTENT_TABLE_EDIT:
            case self::MODULE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy() && PoP_ApplicationProcessors_Utils::addSections()) {
                    return [PoP_Module_Processor_InstantaneousFilters::class, PoP_Module_Processor_InstantaneousFilters::MODULE_INSTANTANEOUSFILTER_CONTENTSECTIONS];
                }
                break;
        }

        return parent::getSectionfilterModule($componentVariation);
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_MYCONTENT_TABLE_EDIT:
            case self::MODULE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW:
            case self::MODULE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}



