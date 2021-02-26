<?php

declare(strict_types=1);

namespace PoPSchema\Users;

use PoP\Root\Component\AbstractComponent;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;

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
        self::initServices(dirname(__DIR__));
        self::initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled()) {
            self::initServices(dirname(__DIR__), '/Conditional/API');
        }
        if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
            self::initServices(dirname(__DIR__), '/Conditional/RESTAPI');
        }

        if (class_exists('\PoPSchema\CustomPosts\Component')) {
            self::initServices(dirname(__DIR__), '/Conditional/CustomPosts');
            self::initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoPSchema\CustomPosts\Component::class, $skipSchemaComponentClasses),
                '/Conditional/CustomPosts'
            );
            if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
                self::initServices(dirname(__DIR__), '/Conditional/CustomPosts/Conditional/RESTAPI');
            }
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
