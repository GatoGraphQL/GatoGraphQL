<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInput;

use PoP\ComponentModel\Facades\FilterInputProcessors\FilterInputProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;

class FilterInputHelper
{
    public static function getFilterInputName(array $filterInput): string
    {
        $filterInputProcessorManager = FilterInputProcessorManagerFacade::getInstance();
        /** @var FormComponentModuleProcessorInterface */
        $filterInputProcessor = $filterInputProcessorManager->getProcessor($filterInput);
        return $filterInputProcessor->getName($filterInput);
    }
}
