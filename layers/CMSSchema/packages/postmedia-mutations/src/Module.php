<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMediaMutations;

use PoP\Root\Module\AbstractModule;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostMediaMutations\Module::class,
            \PoPCMSSchema\PostMutations\Module::class,
        ];
    }

    // Currently there are no services, so disable
    // /**
    //  * Initialize services
    //  *
    //  * @param array<string, mixed> $configuration
    //  * @param string[] $skipSchemaModuleClasses
    //  */
    // protected function initializeContainerServices(
    //     array $configuration,
    //     bool $skipSchema,
    //     array $skipSchemaModuleClasses,
    // ): void {
    //     $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    // }
}
