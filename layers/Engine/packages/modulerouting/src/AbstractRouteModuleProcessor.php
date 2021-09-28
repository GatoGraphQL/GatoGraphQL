<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\Hooks\HooksAPIInterface;

abstract class AbstractRouteModuleProcessor
{
    protected HooksAPIInterface $hooksAPI;
    public function __construct(HooksAPIInterface $hooksAPI)
    {
        $this->hooksAPI = $hooksAPI;
    }

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
