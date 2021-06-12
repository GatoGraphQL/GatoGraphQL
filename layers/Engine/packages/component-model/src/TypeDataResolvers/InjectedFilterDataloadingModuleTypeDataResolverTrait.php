<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeDataResolvers;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

trait InjectedFilterDataloadingModuleTypeDataResolverTrait
{
    public function maybeFilterDataloadQueryArgs(array &$query, array $options = [])
    {
        if ($filterDataloadQueryArgsParams = $options['filter-dataload-query-args'] ?? null) {
            $filterDataloadQueryArgsSource = $filterDataloadQueryArgsParams['source'];
            $filterDataloadingModule = $filterDataloadQueryArgsParams['module'];
            if ($filterDataloadQueryArgsSource && $filterDataloadingModule) {
                $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                $moduleprocessor_manager->getProcessor($filterDataloadingModule)->filterHeadmoduleDataloadQueryArgs($filterDataloadingModule, $query, $filterDataloadQueryArgsSource);
            }
        }
    }
}
