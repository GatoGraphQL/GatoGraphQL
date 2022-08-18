<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ApplicationEvents;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<\PoP\Root\Module\ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Definitions\Module::class,
            \PoP\GraphQLParser\Module::class,
            \PoP\LooseContracts\Module::class,
            \PoP\ComponentRouting\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<class-string<\PoP\Root\Module\ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__));
    }

    public function moduleLoaded(): void
    {
        parent::moduleLoaded();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::MODULE_LOADED);
    }

    public function boot(): void
    {
        parent::boot();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::BOOT);
    }

    public function afterBoot(): void
    {
        parent::afterBoot();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::AFTER_BOOT);
    }
}
