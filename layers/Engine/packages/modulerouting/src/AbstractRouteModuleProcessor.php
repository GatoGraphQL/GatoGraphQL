<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\BasicService\BasicServiceTrait;

abstract class AbstractRouteModuleProcessor
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
