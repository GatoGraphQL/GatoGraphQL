<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMediaMutations;

use PoP\Root\Component\AbstractComponent;

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
            \PoPCMSSchema\CustomPostMediaMutations\Component::class,
            \PoPCMSSchema\PostMutations\Component::class,
        ];
    }

    // Currently there are no services, so disable
    // /**
    //  * Initialize services
    //  *
    //  * @param array<string, mixed> $configuration
    //  * @param string[] $skipSchemaComponentClasses
    //  */
    // protected function initializeContainerServices(
    //     array $configuration,
    //     bool $skipSchema,
    //     array $skipSchemaComponentClasses,
    // ): void {
    //     $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    // }
}
