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
     * @return array<string, array> [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array;

    /**
     * Add schema Module classes to skip initializing
     *
     * @return string[] List of `Module` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array;
}
