<?php

class PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelBlocks extends PoP_Module_Processor_TagTabPanelSectionBlocksBase
{
    public final const MODULE_BLOCK_TABPANEL_TAGLINKS = 'block-tabpanel-taglinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_TABPANEL_TAGLINKS],
        );
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_BLOCK_TABPANEL_TAGLINKS => [PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelComponents::class, PoP_ContentPostLinks_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGLINKS],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getDelegatorfilterSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_TABPANEL_TAGLINKS:
                return [self::class, self::MODULE_FILTER_TAGLINKS];
        }

        return parent::getDelegatorfilterSubmodule($module);
    }
}


