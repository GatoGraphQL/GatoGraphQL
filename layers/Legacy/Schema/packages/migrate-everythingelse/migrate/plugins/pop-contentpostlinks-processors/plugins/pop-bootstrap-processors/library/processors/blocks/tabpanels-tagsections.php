<?php

class PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGLINKS = 'block-tabpanel-taglinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGLINKS],
        );
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGLINKS => [PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelComponents::class, PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGLINKS],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGLINKS:
                return [self::class, self::MODULE_FILTER_TAGLINKS];
        }

        return parent::getDelegatorfilterSubmodule($component);
    }
}


