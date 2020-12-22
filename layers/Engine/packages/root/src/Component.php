<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [];
    }
    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);

        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();

        // Initialize the ContainerBuilder
        // Indicate if to cache the container configuration, from configuration if defined, or from the environment
        $cacheContainerConfiguration =
            $configuration[Environment::CACHE_CONTAINER_CONFIGURATION] ??
            Environment::cacheContainerConfiguration();

        // Provide a namespace, from configuration if defined, or from the environment
        $namespace =
            $configuration[Environment::CONTAINER_CONFIGURATION_CACHE_NAMESPACE] ??
            Environment::getCacheContainerConfigurationNamespace();

            // No need to provide a directory => then it will use a system temp folder
        $directory = null;
        // $directory = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'build' . \DIRECTORY_SEPARATOR . 'cache';
        ContainerBuilderFactory::init($cacheContainerConfiguration, $namespace, $directory);
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        // Compile and Cache Symfony's DependencyInjection Container Builder
        ContainerBuilderFactory::maybeCompileAndCacheContainer();
    }
}
