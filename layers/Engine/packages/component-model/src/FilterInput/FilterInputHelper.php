<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInput;

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface;

class FilterInputHelper
{
    public static function getFilterInputName(array $filterInputModule): string
    {
        $componentProcessorManager = ComponentProcessorManagerFacade::getInstance();
        /** @var FilterInputComponentProcessorInterface */
        $filterInputComponentProcessor = $componentProcessorManager->getProcessor($filterInputModule);
        return $filterInputComponentProcessor->getName($filterInputModule);
    }
}
