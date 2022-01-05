<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Formatters;

use PoP\Engine\App;
use PoP\Engine\Formatters\DateFormatterInterface;

class DateFormatterFacade
{
    public static function getInstance(): DateFormatterInterface
    {
        /**
         * @var DateFormatterInterface
         */
        $service = App::getContainer()->get(DateFormatterInterface::class);
        return $service;
    }
}
