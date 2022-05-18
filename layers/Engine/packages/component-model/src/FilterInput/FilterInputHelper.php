<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInput;

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface;

class FilterInputHelper
{
    public static function getFilterInputName(array $filterInputModule): string
    {
        $moduleProcessorManager = ComponentProcessorManagerFacade::getInstance();
        /** @var FilterInputComponentProcessorInterface */
        $filterInputComponentProcessor = $moduleProcessorManager->getProcessor($filterInputModule);
        return $filterInputComponentProcessor->getName($filterInputModule);
    }
}
