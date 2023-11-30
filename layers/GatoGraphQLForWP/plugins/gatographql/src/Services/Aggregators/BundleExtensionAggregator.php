<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Aggregators;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

class BundleExtensionAggregator
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?ModuleAggregator $moduleAggregator = null;

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
    final public function setModuleAggregator(ModuleAggregator $moduleAggregator): void
    {
        $this->moduleAggregator = $moduleAggregator;
    }
    final protected function getModuleAggregator(): ModuleAggregator
    {
        if ($this->moduleAggregator === null) {
            /** @var ModuleAggregator */
            $moduleAggregator = $this->instanceManager->getInstance(ModuleAggregator::class);
            $this->moduleAggregator = $moduleAggregator;
        }
        return $this->moduleAggregator;
    }

    /**
     * Given a bunch of extensions, retrieve all bundles that
     * comprise them all
     *
     * @param string[] $extensionModules
     * @return string[]
     */
    public function getBundleModulesComprisingAllExtensionModules(
        array $extensionModules
    ): array {
        if ($extensionModules === []) {
            return [];
        }
        
        $extensionBundleModules = [];

        $bundleModules = $this->getModuleAggregator()->getAllModulesOfClass(BundleExtensionModuleResolverInterface::class);
        foreach ($bundleModules as $bundleModule) {
            /** @var BundleExtensionModuleResolverInterface */
            $bundleModuleResolver = $this->getModuleRegistry()->getModuleResolver($bundleModule);
            $bundledExtensionModules = $bundleModuleResolver->getBundledExtensionModules($bundleModule);
            if (array_diff($extensionModules, $bundledExtensionModules) !== []) {
                continue;
            }
            $extensionBundleModules[] = $bundleModule;
        }

        return $extensionBundleModules;
    }
}
