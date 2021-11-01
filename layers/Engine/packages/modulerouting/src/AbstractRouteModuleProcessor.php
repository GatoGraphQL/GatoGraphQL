<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\WithInstanceManagerServiceTrait;

abstract class AbstractRouteModuleProcessor
{
    use WithInstanceManagerServiceTrait;

    private ?HooksAPIInterface $hooksAPI = null;

    final public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    final protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
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
