<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts;

use PoPSchema\LocationPosts\Environment;
use PoP\Root\Component\AbstractComponent;

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
            \PoPSchema\Posts\Component::class,
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
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);

        if (Environment::addLocationPostTypeToCustomPostUnionTypes()) {
            self::maybeInitPHPSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/AddLocationPostTypeToCustomPostUnionTypes');
        }

        if (
            class_exists('\PoPSchema\Tags\Component')
            && !in_array(\PoPSchema\Tags\Component::class, $skipSchemaComponentClasses)
        ) {
            \PoPSchema\LocationPosts\Conditional\Tags\ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }

        if (
            class_exists('\PoPSchema\Users\Component')
            && !in_array(\PoPSchema\Users\Component::class, $skipSchemaComponentClasses)
        ) {
            \PoPSchema\LocationPosts\Conditional\Users\ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }
    }
}
