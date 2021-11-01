<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInput;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\FilterInputModuleProcessorInterface;

class FilterInputHelper
{
    public static function getFilterInputName(array $filterInputModule): string
    {
        $moduleProcessorManager = ModuleProcessorManagerFacade::getInstance();
        /** @var FilterInputModuleProcessorInterface */
        $filterInputModuleProcessor = $moduleProcessorManager->getProcessor($filterInputModule);
        return $filterInputModuleProcessor->getName($filterInputModule);
    }
}
