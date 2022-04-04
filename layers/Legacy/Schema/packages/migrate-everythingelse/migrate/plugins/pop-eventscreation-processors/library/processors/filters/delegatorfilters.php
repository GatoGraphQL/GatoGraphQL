<?php

class PoP_EventsCreation_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_MYEVENTS = 'delegatorfilter-myevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_MYEVENTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_MYEVENTS => [PoP_EventsCreation_Module_Processor_CustomSimpleFilterInners::class, PoP_EventsCreation_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_MYEVENTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



