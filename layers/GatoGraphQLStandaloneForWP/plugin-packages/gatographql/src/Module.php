<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [];
    }

    /**
     * Initialize services for the system container.
     * It uses Convention over Configuration: if the requested files exist,
     * load them.
     */
    protected function initializeSystemContainerServices(): void
    {
        parent::initializeSystemContainerServices();

        $this->initSystemServices(dirname(__DIR__), '/Overrides/Upstream');
        $this->initSystemServices(dirname(__DIR__), '/Overrides/Upstream', 'module-services.yaml');
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        parent::initializeContainerServices(
            $skipSchema,
            $skipSchemaModuleClasses
        );

        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides/Upstream');
        $this->initServices(dirname(__DIR__), '/Overrides/Upstream', 'module-services.yaml');
    }
}
