<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations;

use PoP\API\Component as APIComponent;
use PoP\Root\Component\AbstractComponent;
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
            \PoPSchema\CustomPostMutations\Component::class,
            \PoPSchema\Posts\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\API\Component::class,
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
        self::initServices(dirname(__DIR__));
        self::initSchemaServices(dirname(__DIR__), $skipSchema);
        if (class_exists(APIComponent::class) && APIComponent::isEnabled()) {
            self::initServices(dirname(__DIR__), '/ConditionalOnComponent/API');
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
