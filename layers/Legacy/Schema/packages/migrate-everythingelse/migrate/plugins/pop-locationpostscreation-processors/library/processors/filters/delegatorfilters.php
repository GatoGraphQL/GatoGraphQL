<?php

class PoPSPEM_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_MYLOCATIONPOSTS = 'delegatorfilter-mylocationposts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_MYLOCATIONPOSTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_MYLOCATIONPOSTS => [PoPSPEM_Module_Processor_CustomSimpleFilterInners::class, PoPSPEM_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_MYLOCATIONPOSTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



