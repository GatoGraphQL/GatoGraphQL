<?php

declare(strict_types=1);

namespace PoPSchema\Posts;

use PoP\Root\Component\AbstractComponent;
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
            \PoPSchema\CustomPosts\Component::class,
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
            \PoPSchema\Users\Component::class,
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
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);

        if (class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled()) {
            self::initYAMLServices(dirname(__DIR__), '/Conditional/API');
        }
        if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
            self::initYAMLServices(dirname(__DIR__), '/Conditional/RESTAPI');
        }

        if (class_exists('\PoPSchema\Users\Component')) {
            self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/Users');
            if (!in_array(\PoPSchema\Users\Component::class, $skipSchemaComponentClasses)) {
                self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/Users');
            }
            if (class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled()) {
                self::initYAMLServices(dirname(__DIR__), '/Conditional/Users/Conditional/API');
            }
            if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
                self::initYAMLServices(dirname(__DIR__), '/Conditional/Users/Conditional/RESTAPI');
            }
        }

        if (ComponentConfiguration::addPostTypeToCustomPostUnionTypes()) {
            self::maybeInitPHPSchemaServices(self::$COMPONENT_DIR, $skipSchema, '/ConditionalOnEnvironment/AddPostTypeToCustomPostUnionTypes');
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
        if (!defined('POP_POSTS_ROUTE_POSTS')) {
            $definitionManager = DefinitionManagerFacade::getInstance();
            define('POP_POSTS_ROUTE_POSTS', $definitionManager->getUniqueDefinition('posts', DefinitionGroups::ROUTES));
        }
    }
}
