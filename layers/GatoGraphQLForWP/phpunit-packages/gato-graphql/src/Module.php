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
            \PHPUnitForGatoGraphQL\DummySchema\Module::class,
            \PHPUnitForGatoGraphQL\DummyWPSchema\Module::class,
            \PHPUnitForGatoGraphQL\WPFakerSchema\Module::class,
            \PHPUnitForGatoGraphQL\WebserverRequests\Module::class,
        ];
    }
}
