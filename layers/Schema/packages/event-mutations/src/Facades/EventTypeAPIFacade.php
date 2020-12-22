<?php

declare(strict_types=1);

namespace PoPSchema\EventMutations\Facades;

use PoPSchema\EventMutations\TypeAPIs\EventMutationTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EventMutationTypeAPIFacade
{
    public static function getInstance(): EventMutationTypeAPIInterface
    {
        /**
         * @var EventMutationTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(EventMutationTypeAPIInterface::class);
        return $service;
    }
}
