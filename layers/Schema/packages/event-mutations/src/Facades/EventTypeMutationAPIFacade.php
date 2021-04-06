<?php

declare(strict_types=1);

namespace PoPSchema\EventMutations\Facades;

use PoPSchema\EventMutations\TypeAPIs\EventTypeMutationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EventTypeMutationAPIFacade
{
    public static function getInstance(): EventTypeMutationAPIInterface
    {
        /**
         * @var EventTypeMutationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(EventTypeMutationAPIInterface::class);
        return $service;
    }
}
