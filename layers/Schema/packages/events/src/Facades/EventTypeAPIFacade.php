<?php

declare(strict_types=1);

namespace PoPSchema\Events\Facades;

use PoPSchema\Events\TypeAPIs\EventTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EventTypeAPIFacade
{
    public static function getInstance(): EventTypeAPIInterface
    {
        /**
         * @var EventTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(EventTypeAPIInterface::class);
        return $service;
    }
}
