<?php

class PoP_AddHighlights_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public const MODULE_DELEGATORFILTER_AUTHORHIGHLIGHTS = 'delegatorfilter-authorhighlights';
    public const MODULE_DELEGATORFILTER_HIGHLIGHTS = 'delegatorfilter-highlights';
    public const MODULE_DELEGATORFILTER_MYHIGHLIGHTS = 'delegatorfilter-myhighlights';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_HIGHLIGHTS],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORHIGHLIGHTS],
            [self::class, self::MODULE_DELEGATORFILTER_MYHIGHLIGHTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::class, PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_HIGHLIGHTS],
            self::MODULE_DELEGATORFILTER_AUTHORHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::class, PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORHIGHLIGHTS],
            self::MODULE_DELEGATORFILTER_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::class, PoP_AddHighlights_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_MYHIGHLIGHTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



