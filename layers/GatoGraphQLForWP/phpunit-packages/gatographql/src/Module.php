<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PHPUnitForGatoGraphQL\WPFakerSchema\Module::class,
            \PHPUnitForGatoGraphQL\WebserverRequests\Module::class,
            \PoP\GuzzleHTTP\Module::class,
        ];
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
        $this->initServices(dirname(__DIR__));
    }
}
