<?php

declare(strict_types=1);

namespace PoPSchema\LocationPostsWP;

use PoP\Root\Component\AbstractComponent;
use PoPSchema\LocationPosts\Environment;

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
            \PoPSchema\LocationPosts\Component::class,
            \PoPSchema\PostsWP\Component::class,
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
        self::initYAMLServices(dirname(__DIR__));
        if (Environment::addLocationPostTypeToCustomPostUnionTypes()) {
            self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/AddLocationPostTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
