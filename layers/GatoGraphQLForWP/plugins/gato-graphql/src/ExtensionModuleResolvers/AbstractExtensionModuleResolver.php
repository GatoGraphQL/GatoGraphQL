<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ExtensionModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;

/**
 * Container modules to display documentation for extensions
 * in the Extensions page.
 */
abstract class AbstractExtensionModuleResolver extends AbstractModuleResolver
{
    use ExtensionModuleResolverTrait;
    
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

    public function getName(string $module): string
    {
        return '';
    }

    public function getDescription(string $module): string
    {
        return '';
    }
}
