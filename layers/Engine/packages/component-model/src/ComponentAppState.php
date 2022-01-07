<?php

declare(strict_types=1);

namespace PoP\Root\Component;

class AbstractComponentAppState extends AbstractComponentAppState
{
    /**
     * Have the Component set its own state, accessible for all Components in the App
     *
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        
    }
}
