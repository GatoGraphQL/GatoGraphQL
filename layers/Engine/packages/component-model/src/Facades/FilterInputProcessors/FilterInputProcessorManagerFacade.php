<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\FilterInputProcessors;

use PoP\Root\App;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;

class FilterInputProcessorManagerFacade
{
    public static function getInstance(): FilterInputProcessorManagerInterface
    {
        /**
         * @var FilterInputProcessorManagerInterface
         */
        $service = App::getContainer()->get(FilterInputProcessorManagerInterface::class);
        return $service;
    }
}
