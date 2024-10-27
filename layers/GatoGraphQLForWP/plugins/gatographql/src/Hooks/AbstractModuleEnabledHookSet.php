<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Hooks;

use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractModuleEnabledHookSet extends AbstractHookSet
{
    private ?ModuleRegistryInterface $moduleRegistry = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->getModuleRegistry()->isModuleEnabled($enablingModule)
                && parent::isServiceEnabled();
        }
        return parent::isServiceEnabled();
    }
}
