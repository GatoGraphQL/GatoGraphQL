<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Component\ComponentAppStateInterface;

interface AccessControlRuleBlockRegistryInterface
{
    public function addAppStateProvider(ComponentAppStateInterface $appStateProvider): void;
    /**
     * @return ComponentAppStateInterface[]
     */
    public function getAppStateProviders(): array;
}
