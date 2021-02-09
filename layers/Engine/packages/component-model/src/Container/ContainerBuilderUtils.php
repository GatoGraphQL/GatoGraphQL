<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Facades\Registries\FieldInterfaceRegistryFacade;
use PoP\Root\Container\ContainerBuilderUtils as RootContainerBuilderUtils;

class ContainerBuilderUtils extends RootContainerBuilderUtils
{
    /**
     * Register all fieldInterfaceResolvers located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function registerFieldInterfaceResolversFromNamespace(string $namespace, bool $includeSubfolders = true): void
    {
        /**
         * Check the registries are enabled
         */
        if (!ComponentConfiguration::enableSchemaEntityRegistries()) {
            return;
        }
        $fieldInterfaceRegistry = FieldInterfaceRegistryFacade::getInstance();
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
            $fieldInterfaceRegistry->addFieldInterfaceResolverClass($serviceClass);
        }
    }

    /**
     * Attach all typeResolverPickers located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function attachTypeResolverPickersFromNamespace(string $namespace, bool $includeSubfolders = true, int $priority = 10): void
    {
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
            $serviceClass::attach(AttachableExtensionGroups::TYPERESOLVERPICKERS, $priority);
        }
    }

    /**
     * Attach all typeResolverDecorators located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function attachTypeResolverDecoratorsFromNamespace(string $namespace, bool $includeSubfolders = true, int $priority = 10): void
    {
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
            $serviceClass::attach(AttachableExtensionGroups::TYPERESOLVERDECORATORS, $priority);
        }
    }
}
