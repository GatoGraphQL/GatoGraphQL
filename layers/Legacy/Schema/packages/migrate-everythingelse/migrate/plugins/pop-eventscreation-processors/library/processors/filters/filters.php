<?php

class PoP_EventsCreation_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_MYEVENTS = 'filter-myevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_MYEVENTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_MYEVENTS => [PoP_EventsCreation_Module_Processor_CustomFilterInners::class, PoP_EventsCreation_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYEVENTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



