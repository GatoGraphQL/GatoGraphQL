<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElseWP;

use PoPSchema\EverythingElseWP\Conditional\CustomPosts\ConditionalComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;

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
            \PoPSchema\EverythingElse\Component::class,
            \PoP\EngineWP\Component::class,
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
            \PoPSchema\CustomPosts\Component::class,
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
}
