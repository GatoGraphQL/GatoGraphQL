<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Component\ApplicationEvents;

/**
 * A service which must always be instantiated,
 * so it's done automatically by the application.
 * Eg: hooks.
 */
abstract class AbstractAutomaticallyInstantiatedService implements AutomaticallyInstantiatedServiceInterface
{
    public function initialize(): void
    {
        // By default, do nothing
    }

    public function getInstantiationEvent(): string
    {
        return ApplicationEvents::BEFORE_BOOT;
    }
}
