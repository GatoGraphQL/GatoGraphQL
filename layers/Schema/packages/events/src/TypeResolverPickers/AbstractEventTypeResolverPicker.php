<?php

declare(strict_types=1);

namespace PoPSchema\Events\TypeResolverPickers;

use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;
use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;

abstract class AbstractEventTypeResolverPicker extends AbstractTypeResolverPicker
{
    public function getTypeResolverClass(): string
    {
        return EventTypeResolver::class;
    }

    public function isInstanceOfType(object $object): bool
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        return $eventTypeAPI->isInstanceOfEventType($object);
    }

    public function isIDOfType($resultItemID): bool
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        return $eventTypeAPI->eventExists($resultItemID);
    }
}
