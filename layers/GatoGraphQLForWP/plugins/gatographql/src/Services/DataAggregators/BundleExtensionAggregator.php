<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataAggregators;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

class BundleExtensionAggregator
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
     * Given a bunch of extensions, retrieve all bundles that
     * comprise them all
     *
     * @param string[] $extensionModules
     * @return string[]
     */
    public function getBundleModulesComprisingAllExtensionModules(
        array $extensionModules
    ): array {
        $extensionBundleModules = [];

        foreach ($this->getAllBundleModules() as $bundleModule) {
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

    /**
     * Produce the list of all the Gato GraphQL Bundle modules
     *
     * @return string[]
     */
    public function getAllBundleModules(): array
    {
        $modules = $this->getModuleRegistry()->getAllModules();
        $bundleModules = [];
        foreach ($modules as $module) {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            if (!($moduleResolver instanceof BundleExtensionModuleResolverInterface)) {
                continue;
            }
            $bundleModules = [
                ...$bundleModules,
                ...$moduleResolver->getModulesToResolve(),
            ];
        }
        return $bundleModules;
    }
}
