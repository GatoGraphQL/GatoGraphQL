<?php

declare(strict_types=1);

namespace PoPSchema\Users;

use PoP\Root\Component\AbstractComponent;
use PoPSchema\Users\Config\ServiceConfiguration;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
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
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
        ComponentConfiguration::setConfiguration($configuration);
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::initYAMLServices(self::$COMPONENT_DIR);
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);

        if (
            class_exists('\PoP\API\Component')
            && !in_array(\PoP\API\Component::class, $skipSchemaComponentClasses)
        ) {
            self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/API');
        }

        if (
            class_exists('\PoP\RESTAPI\Component')
            && !in_array(\PoP\RESTAPI\Component::class, $skipSchemaComponentClasses)
        ) {
            self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/RESTAPI');
        }

        if (class_exists('\PoPSchema\CustomPosts\Component')) {
            self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/CustomPosts');
            if (!in_array(\PoPSchema\CustomPosts\Component::class, $skipSchemaComponentClasses)) {
                self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/CustomPosts');
                if (
                    class_exists('\PoP\RESTAPI\Component')
                    && !in_array(\PoP\RESTAPI\Component::class, $skipSchemaComponentClasses)
                ) {
                    self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/CustomPosts/Conditional/RESTAPI');
                }
            }
        }
        ServiceConfiguration::initialize();
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
