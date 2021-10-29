<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\WithInstanceManagerServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRouteModuleProcessor
{
    use WithHooksAPIServiceTrait;
    use WithInstanceManagerServiceTrait;

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
