<?php

declare(strict_types=1);

namespace PoP\Root\State;

abstract class AbstractAppStateProvider implements AppStateProviderInterface
{
    public function __construct(
        protected ComponentInterface $component
    ) {
    }

    public function augment(array &$state): void
    {
    }
}
