<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts;

use PoPSchema\LocationPosts\Environment;
use PoP\Root\Component\AbstractComponent;
use PoPSchema\Users\Component as UsersComponent;
use PoPSchema\Tags\Component as TagsComponent;

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
            \PoPSchema\Posts\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoPSchema\Users\Component::class,
            \PoPSchema\Tags\Component::class,
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
        self::initSchemaServices(dirname(__DIR__), $skipSchema);

        if (Environment::addLocationPostTypeToCustomPostUnionTypes()) {
            self::initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddLocationPostTypeToCustomPostUnionTypes');
        }

        if (class_exists(TagsComponent::class)) {
            self::initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoPSchema\Tags\Component::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/Tags'
            );
        }

        if (class_exists(UsersComponent::class)) {
            self::initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersComponent::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/Users'
            );
        }
    }
}
