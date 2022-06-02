<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInput;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class FilterInputHelper
{
    public static function getFilterInputName(Component $filterInputComponent): string
    {
        $componentProcessorManager = ComponentProcessorManagerFacade::getInstance();
        /** @var FilterInputComponentProcessorInterface */
        $filterInputComponentProcessor = $componentProcessorManager->getComponentProcessor($filterInputComponent);
        return $filterInputComponentProcessor->getName($filterInputComponent);
    }
}
