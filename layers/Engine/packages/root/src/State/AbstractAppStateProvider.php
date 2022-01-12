<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\BasicService\BasicServiceTrait;
use PoP\Root\Services\ServiceTrait;

abstract class AbstractAppStateProvider implements AppStateProviderInterface
{
    use BasicServiceTrait;
    use ServiceTrait;

    public function initialize(array &$state): void
    {
    }

    public function consolidate(array &$state): void
    {
    }

    public function augment(array &$state): void
    {
    }

    public function compute(array &$state): void
    {
    }
}
