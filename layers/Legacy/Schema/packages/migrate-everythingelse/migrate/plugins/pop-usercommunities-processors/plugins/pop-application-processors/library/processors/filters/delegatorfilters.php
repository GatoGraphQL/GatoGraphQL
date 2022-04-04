<?php

class PoP_UserCommunities_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const MODULE_DELEGATORFILTER_MYMEMBERS = 'delegatorfilter-mymembers';
    public final const MODULE_DELEGATORFILTER_COMMUNITIES = 'delegatorfilter-communities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_MYMEMBERS],
            [self::class, self::MODULE_DELEGATORFILTER_COMMUNITIES],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_MYMEMBERS => [GD_URE_Module_Processor_CustomSimpleFilterInners::class, GD_URE_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_MYMEMBERS],
            self::MODULE_DELEGATORFILTER_COMMUNITIES => [GD_URE_Module_Processor_CustomSimpleFilterInners::class, GD_URE_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_COMMUNITIES],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



