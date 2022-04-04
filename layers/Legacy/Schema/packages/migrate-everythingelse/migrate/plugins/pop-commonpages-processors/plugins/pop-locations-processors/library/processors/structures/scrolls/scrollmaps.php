<?php

class PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public final const MODULE_SCROLL_WHOWEARE_MAP = 'scroll-whoweare-map';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_WHOWEARE_MAP],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_WHOWEARE_MAP => [PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners::class, PoP_CommonPagesProcessors_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_WHOWEARE_MAP],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


