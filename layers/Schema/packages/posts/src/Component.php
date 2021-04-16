<?php

declare(strict_types=1);

namespace PoPSchema\Posts;

use PoP\Root\Component\AbstractComponent;
use PoP\Routing\DefinitionGroups;
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\RESTAPI\Component as RESTAPIComponent;
use PoP\API\Component as APIComponent;
use PoPSchema\Users\Component as UsersComponent;

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
            \PoPSchema\CustomPosts\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\API\Component::class,
            \PoP\RESTAPI\Component::class,
            \PoPSchema\Users\Component::class,
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
        ComponentConfiguration::setConfiguration($configuration);
        self::initServices(dirname(__DIR__));
        self::initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists(APIComponent::class) && APIComponent::isEnabled()) {
            self::initServices(dirname(__DIR__), '/Conditional/API');
        }
        if (class_exists(RESTAPIComponent::class) && RESTAPIComponent::isEnabled()) {
            self::initServices(dirname(__DIR__), '/Conditional/RESTAPI');
        }

        if (class_exists(UsersComponent::class)) {
            self::initServices(
                dirname(__DIR__),
                '/Conditional/Users'
            );
            self::initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersComponent::class, $skipSchemaComponentClasses),
                '/Conditional/Users'
            );
            if (class_exists(APIComponent::class) && APIComponent::isEnabled()) {
                self::initServices(dirname(__DIR__), '/Conditional/Users/Conditional/API');
            }
            if (class_exists(RESTAPIComponent::class) && RESTAPIComponent::isEnabled()) {
                self::initServices(dirname(__DIR__), '/Conditional/Users/Conditional/RESTAPI');
            }
        }

        if (ComponentConfiguration::addPostTypeToCustomPostUnionTypes()) {
            self::initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/AddPostTypeToCustomPostUnionTypes');
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
