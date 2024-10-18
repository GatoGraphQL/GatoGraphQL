<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractStandaloneModuleConfiguration extends ModuleConfiguration
{
    /**
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
