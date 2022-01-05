<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Formatters;

use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class DateFormatterFacade
{
    public static function getInstance(): DateFormatterInterface
    {
        /**
         * @var DateFormatterInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(DateFormatterInterface::class);
        return $service;
    }
}
