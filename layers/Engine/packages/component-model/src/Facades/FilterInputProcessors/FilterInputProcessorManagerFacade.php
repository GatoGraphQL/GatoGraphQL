<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\FilterInputs;

use PoP\Root\App;
use PoP\ComponentModel\FilterInputs\FilterInputManagerInterface;

class FilterInputManagerFacade
{
    public static function getInstance(): FilterInputManagerInterface
    {
        /**
         * @var FilterInputManagerInterface
         */
        $service = App::getContainer()->get(FilterInputManagerInterface::class);
        return $service;
    }
}
