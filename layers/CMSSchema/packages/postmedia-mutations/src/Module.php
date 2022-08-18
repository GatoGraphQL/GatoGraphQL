<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMediaMutations;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<\PoP\Root\Module\ModuleInterface>>
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
    //  * @param array<string,mixed> $configuration
    //  * @param array<class-string<\PoP\Root\Module\ModuleInterface>> $skipSchemaModuleClasses
    //  */
    // protected function initializeContainerServices(
    //     array $configuration,
    //     bool $skipSchema,
    //     array $skipSchemaModuleClasses,
    // ): void {
    //     $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    // }
}
