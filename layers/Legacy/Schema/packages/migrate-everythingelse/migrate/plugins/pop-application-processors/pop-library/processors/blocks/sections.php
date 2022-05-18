<?php

class PoP_Module_Processor_SectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS = 'block-singleauthors-scroll-details';
    public final const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW = 'block-singleauthors-scroll-fullview';
    public final const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL = 'block-singleauthors-scroll-thumbnail';
    public final const MODULE_BLOCK_SINGLEAUTHORS_SCROLL_LIST = 'block-singleauthors-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS => POP_ROUTE_AUTHORS,
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW => POP_ROUTE_AUTHORS,
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_LIST => POP_ROUTE_AUTHORS,
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL => POP_ROUTE_AUTHORS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_LIST => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
         // Single Authors has no filter, so show only the Share control
            case self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLEAUTHORS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_SUBMENUSHARE];
        }

        return parent::getControlgroupTopSubmodule($component);
    }
}



