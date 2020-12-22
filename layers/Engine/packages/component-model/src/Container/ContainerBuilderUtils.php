<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Facades\Registries\FieldInterfaceRegistryFacade;
use PoP\Root\Container\ContainerBuilderUtils as RootContainerBuilderUtils;

class ContainerBuilderUtils extends RootContainerBuilderUtils
{
    /**
     * Register all typeResolvers located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function registerTypeResolversFromNamespace(string $namespace, bool $includeSubfolders = true): void
    {
        /**
         * Check the registries are enabled
         */
        if (!ComponentConfiguration::enableSchemaEntityRegistries()) {
            return;
        }
        /**
         * We can't save this output into the cached container through `injectValuesIntoService`,
         * because the container must be compiled to call `getServiceClassesUnderNamespace`, and once
         * compiled can't add more data.
         * And once compiled it is cached, and it will not contain any new data added after it is cached,
         * which is executed in `beforeBoot` at the vey beginning (through `maybeCompileAndCacheContainer`)
         * Hence, the values are injected into the service directly, and not through its proxy
         */
        $typeRegistry = TypeRegistryFacade::getInstance();
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
            $typeRegistry->addTypeResolverClass($serviceClass);
        }
    }
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
     * Attach all fieldResolvers located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function attachFieldResolversFromNamespace(string $namespace, bool $includeSubfolders = true, int $priority = 10): void
    {
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
            $serviceClass::attach(AttachableExtensionGroups::FIELDRESOLVERS, $priority);
        }
    }

    /**
     * Attach all directiveResolvers located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function attachAndRegisterDirectiveResolversFromNamespace(string $namespace, bool $includeSubfolders = true, int $priority = 10): void
    {
        /**
         * Check the registries are enabled
         */
        $enableSchemaEntityRegistries = ComponentConfiguration::enableSchemaEntityRegistries();
        /**
         * We can't save this output into the cached container through `injectValuesIntoService`,
         * because the container must be compiled to call `getServiceClassesUnderNamespace`, and once
         * compiled can't add more data.
         * And once compiled it is cached, and it will not contain any new data added after it is cached,
         * which is executed in `beforeBoot` at the vey beginning (through `maybeCompileAndCacheContainer`)
         * Hence, the values are injected into the service directly, and not through its proxy
         */
        $directiveRegistry = $enableSchemaEntityRegistries ? DirectiveRegistryFacade::getInstance() : null;
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
            $serviceClass::attach(AttachableExtensionGroups::DIRECTIVERESOLVERS, $priority);

            // Register the directive in the registry. If cached, do not execute or it will throw exception
            if ($enableSchemaEntityRegistries) {
                $directiveRegistry->addDirectiveResolverClass($serviceClass);
            }
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
