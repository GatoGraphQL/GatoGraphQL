<?php

class GD_URE_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_INDIVIDUALS = 'delegatorfilter-individuals';
    public final const MODULE_DELEGATORFILTER_ORGANIZATIONS = 'delegatorfilter-organizations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_INDIVIDUALS],
            [self::class, self::MODULE_DELEGATORFILTER_ORGANIZATIONS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_INDIVIDUALS => [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_INDIVIDUALS],
            self::MODULE_DELEGATORFILTER_ORGANIZATIONS => [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_ORGANIZATIONS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



