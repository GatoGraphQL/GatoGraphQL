<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP;

use Leoloso\ExamplesForPoP\Container\CompilerPasses\ConfigurePersistedFragmentCompilerPass;
use Leoloso\ExamplesForPoP\Container\CompilerPasses\ConfigurePersistedQueryCompilerPass;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public const VERSION = '0.2.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \GraphQLByPoP\GraphQLServer\Component::class,
            \PoPSchema\Media\Component::class,
            \PoPSchema\TranslateDirective\Component::class,
            \PoPSchema\CDNDirective\Component::class,
        ];
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
        self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema);
        if (ComponentModelComponentConfiguration::useComponentModelCache()) {
            self::maybeInitPHPSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/UseComponentModelCache');
        }
    }

    /**
     * Get all the compiler pass classes required to register on the container
     *
     * @return string[]
     */
    public static function getContainerCompilerPassClasses(): array
    {
        return [
            ConfigurePersistedFragmentCompilerPass::class,
            ConfigurePersistedQueryCompilerPass::class,
        ];
    }
}
