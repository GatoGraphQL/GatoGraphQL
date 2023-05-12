<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ExtensionModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;

abstract class AbstractExtensionModuleResolver extends AbstractModuleResolver
{
    /**
     * The type of the module doesn't matter, as these modules
     * are all hidden anyway
     */
    public function getModuleType(string $module): string
    {
        return '';
    }

    public function isHidden(string $module): bool
    {
        return true;
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return true;
    }
}
