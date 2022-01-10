<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\Root\Services\ServiceTrait;

abstract class AbstractAppStateProvider implements AppStateProviderInterface
{
    use ServiceTrait;

    public function consolidate(array &$state): void
    {
    }
    
    public function augment(array &$state): void
    {
    }
}
