<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractStandaloneModuleConfiguration extends ModuleConfiguration
{
    /**
     * The StandaloneModule must be applied the configuration
     * for itself, and also for the upstream Module.
     *
     * @phpstan-return array<class-string<ModuleInterface>>
     */
    protected function getModuleClasses(): array
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        /** @var array<class-string<ModuleInterface>> */
        return [
            Module::class,
            $classNamespace . '\\' . $this->getModuleClassname(),
        ];
    }

    protected function getModuleClassname(): string
    {
        return 'StandaloneModule';
    }
}
