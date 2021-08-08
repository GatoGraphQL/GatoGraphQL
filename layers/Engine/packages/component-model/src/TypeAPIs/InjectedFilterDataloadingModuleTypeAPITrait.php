<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeAPIs;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\QueryDataModuleProcessorInterface;

trait InjectedFilterDataloadingModuleTypeAPITrait
{
    public function maybeFilterDataloadQueryArgs(array &$query, array $options = [])
    {
        if ($filterDataloadQueryArgsParams = $options['filter-dataload-query-args'] ?? null) {
            $filterDataloadQueryArgsSource = $filterDataloadQueryArgsParams['source'];
            $filterDataloadingModule = $filterDataloadQueryArgsParams['module'];
            if ($filterDataloadQueryArgsSource && $filterDataloadingModule) {
                $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                /** @var QueryDataModuleProcessorInterface */
                $queryDataModuleProcessor = $moduleprocessor_manager->getProcessor($filterDataloadingModule);
                $queryDataModuleProcessor->filterHeadmoduleDataloadQueryArgs($filterDataloadingModule, $query, $filterDataloadQueryArgsSource);
            }
        }
    }
}
