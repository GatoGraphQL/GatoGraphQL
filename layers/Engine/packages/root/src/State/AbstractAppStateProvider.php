<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\Root\Services\AbstractBasicService;
use PoP\Root\Services\ServiceTrait;

abstract class AbstractAppStateProvider extends AbstractBasicService implements AppStateProviderInterface
{
    use ServiceTrait;

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
    }

    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(array &$state): void
    {
    }

    /**
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
    }

    /**
     * @param array<string,mixed> $state
     */
    public function compute(array &$state): void
    {
    }

    /**
     * @param array<string,mixed> $state
     */
    public function execute(array &$state): void
    {
    }
}
