<?php

declare(strict_types=1);

namespace PoPWPSchema\Media;

use PoP\BasicService\Component\AbstractComponent;

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
            \PoPSchema\MediaWP\Component::class,
            \PoPWPSchema\CustomPosts\Component::class,
            \PoPWPSchema\SchemaCommons\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
        $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/Overrides');
    }
}
