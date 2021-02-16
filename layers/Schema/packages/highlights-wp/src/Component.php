<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP;

use PoP\Root\Component\AbstractComponent;
use PoPSchema\Highlights\Environment;

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
            \PoPSchema\Highlights\Component::class,
            \PoPSchema\CustomPostsWP\Component::class,
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
        if (Environment::addHighlightTypeToCustomPostUnionTypes()) {
            self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/AddHighlightTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
