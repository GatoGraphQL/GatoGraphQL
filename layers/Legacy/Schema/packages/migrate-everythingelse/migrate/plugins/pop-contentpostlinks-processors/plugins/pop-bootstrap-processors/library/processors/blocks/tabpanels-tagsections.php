<?php

class PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const COMPONENT_BLOCK_TABPANEL_TAGLINKS = 'block-tabpanel-taglinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_TABPANEL_TAGLINKS],
        );
    }

    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        $inners = array(
            self::COMPONENT_BLOCK_TABPANEL_TAGLINKS => [PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelComponents::class, PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGLINKS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TABPANEL_TAGLINKS:
                return [self::class, self::COMPONENT_FILTER_TAGLINKS];
        }

        return parent::getDelegatorfilterSubcomponent($component);
    }
}


