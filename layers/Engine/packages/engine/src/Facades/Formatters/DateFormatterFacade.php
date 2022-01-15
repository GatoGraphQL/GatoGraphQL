<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Formatters;

use PoP\Root\App;
use PoPSchema\SchemaCommons\Formatters\DateFormatterInterface;

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
