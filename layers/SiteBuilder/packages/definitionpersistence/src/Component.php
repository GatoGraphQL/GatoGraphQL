<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\DefinitionPersistence\Container\CompilerPasses\ConfigureDefinitionPersistenceCompilerPass;
use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\Definitions\Component::class,
            \PoP\FileStore\Component::class,
            \PoP\ComponentModel\Component::class,
        ];
    }

    public static function getBuildDir(): string
    {
        return dirname(__DIR__) . '/build';
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
        self::initYAMLServices(dirname(__DIR__));
    }

    /**
     * Get all the compiler pass classes required to register on the container
     *
     * @return string[]
     */
    public static function getContainerCompilerPassClasses(): array
    {
        return [
            ConfigureDefinitionPersistenceCompilerPass::class,
        ];
    }
}
