<?php

declare(strict_types=1);

namespace PoP\Root\Component;

abstract class AbstractComponentAppState implements ComponentAppStateInterface
{
    public function __construct(
        protected ComponentInterface $component
    ) {
    }

    /**
     * Once all properties by all Components have been set,
     * have this second pass consolidate the state
     *
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
    }
}
