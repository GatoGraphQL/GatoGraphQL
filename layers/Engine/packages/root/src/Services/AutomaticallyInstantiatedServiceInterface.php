<?php

declare(strict_types=1);

namespace PoP\Root\Services;

/**
 * A service which must always be instantiated,
 * so it's done automatically by the application.
 * Eg: hooks.
 */
interface AutomaticallyInstantiatedServiceInterface extends ActivableServiceInterface, BasicServiceInterface
{
    public function initialize(): void;
    public function getInstantiationEvent(): string;
}
