<?php

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_WHOWEARE_SCROLLMAP = 'block-whoweare-scrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_WHOWEARE_SCROLLMAP],
        );
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_WHOWEARE_SCROLLMAP => [GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_WHOWEARE_SCROLLMAP],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }
}



