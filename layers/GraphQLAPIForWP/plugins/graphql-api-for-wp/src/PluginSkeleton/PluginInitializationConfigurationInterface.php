<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

interface PluginInitializationConfigurationInterface
{
    /**
     * Initialize all configuration
     */
    public function initialize(): void;

    /**
     * Provide the configuration for all components required in the plugin
     *
     * @return array<string, array> [key]: Component class, [value]: Configuration
     */
    public function getComponentClassConfiguration(): array;

    /**
     * Add schema Component classes to skip initializing
     *
     * @return string[] List of `Component` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array;

    /**
     * Add Component classes to disable
     *
     * @return string[] List of `Component` class which must not be enabled
     */
    public function getComponentClassesToDisable(): array;
}
