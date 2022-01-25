<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\ApplicationEvents;

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
            \PoP\Definitions\Component::class,
            \PoP\FieldQuery\Component::class,
            \PoP\GraphQLParser\Component::class,
            \PoP\LooseContracts\Component::class,
            \PoP\ModuleRouting\Component::class,
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
        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__));
    }

    public function componentLoaded(): void
    {
        parent::componentLoaded();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::COMPONENT_LOADED);
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
