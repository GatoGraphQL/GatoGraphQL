<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations;

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
            \PoPSitesWassup\CustomPostLinkMutations\Component::class,
            \PoPSitesWassup\PostLinkMutations\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }
}
