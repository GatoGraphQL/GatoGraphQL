<?php

declare(strict_types=1);

namespace PoPSchema\Comments;

use PoP\API\Component as APIComponent;
use PoP\RESTAPI\Component as RESTAPIComponent;
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
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\CustomPosts\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
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
        self::initServices(dirname(__DIR__));
        self::initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists(APIComponent::class) && APIComponent::isEnabled()) {
            self::initServices(dirname(__DIR__), '/ConditionalOnComponent/API');
        }

        if (class_exists(RESTAPIComponent::class) && RESTAPIComponent::isEnabled()) {
            self::initServices(dirname(__DIR__), '/ConditionalOnComponent/RESTAPI');
        }

        if (class_exists(UsersComponent::class)) {
            self::initServices(
                dirname(__DIR__),
                '/ConditionalOnComponent/Users'
            );
            self::initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersComponent::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/Users'
            );
        }
    }
}
