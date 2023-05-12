<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;

class AccessControlVisitorIPExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    private const EXTENSION_SLUG = 'access-control-visitor-ip';
    private const EXTENSION_MODULE = Plugin::NAMESPACE . '\\' . 'extensions' . '\\' . self::EXTENSION_SLUG;

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::EXTENSION_MODULE,
        ];
    }

    protected function getExtensionSubfolder(): string
    {
        return self::EXTENSION_SLUG;
    }
}
