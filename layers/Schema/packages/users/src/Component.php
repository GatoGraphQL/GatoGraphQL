<?php

declare(strict_types=1);

namespace PoPSchema\Users;

use PoPSchema\Users\Conditional\CustomPosts\ConditionalComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoPSchema\Users\Config\ServiceConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;

    public static $COMPONENT_DIR;

    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\QueriedObject\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\API\Component::class,
            \PoP\RESTAPI\Component::class,
            \PoPSchema\CustomPosts\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        $packageName = basename(dirname(__DIR__));
        $folder = dirname(__DIR__, 2);
        return [
            $folder . '/migrate-' . $packageName . '/initialize.php',
        ];
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
        ComponentConfiguration::setConfiguration($configuration);
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::initYAMLServices(self::$COMPONENT_DIR);
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);
        ServiceConfiguration::initialize();

        if (
            class_exists('\PoPSchema\CustomPosts\Component')
            && !in_array(\PoPSchema\CustomPosts\Component::class, $skipSchemaComponentClasses)
        ) {
            ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize all classes
        ContainerBuilderUtils::registerFieldInterfaceResolversFromNamespace(__NAMESPACE__ . '\\FieldInterfaceResolvers');

        // Initialize all conditional components
        if (!empty(ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Conditional\\CustomPosts\\FieldResolvers'))) {
            ConditionalComponent::beforeBoot();
        }
    }

    /**
     * Define runtime constants
     */
    protected static function defineRuntimeConstants(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if (!defined('POP_USERS_ROUTE_USERS')) {
            $definitionManager = DefinitionManagerFacade::getInstance();
            define('POP_USERS_ROUTE_USERS', $definitionManager->getUniqueDefinition('users', DefinitionGroups::ROUTES));
        }
    }
}
