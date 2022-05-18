<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractComponentRoutingProcessor
{
    use BasicServiceTrait;

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array();
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        return array();
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        return array();
    }

    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        return array();
    }
}
