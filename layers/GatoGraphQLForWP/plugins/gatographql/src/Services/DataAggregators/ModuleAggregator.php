<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataAggregators;

use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

class ModuleAggregator
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    /**
     * Produce the list of all the modules for some class
     *
     * @return string[]
     * @phpstan-return class-string<ModuleResolverInterface>
     */
    public function getAllModulesOfClass(string $class): array
    {
        $modules = [];
        foreach ($this->getModuleRegistry()->getAllModules() as $module) {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            if (!\is_a($moduleResolver, $class, true)) {
                continue;
            }
            $modules = [
                ...$modules,
                ...$moduleResolver->getModulesToResolve(),
            ];
        }
        return $modules;
    }
}
