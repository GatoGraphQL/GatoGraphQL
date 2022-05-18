<?php

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_WHOWEARE_SCROLLMAP = 'block-whoweare-scrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_WHOWEARE_SCROLLMAP],
        );
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_WHOWEARE_SCROLLMAP => [GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }
}



