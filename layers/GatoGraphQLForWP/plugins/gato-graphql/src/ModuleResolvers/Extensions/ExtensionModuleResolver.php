<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;

class ExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    public final const ACCESS_CONTROL_VISITOR_IP = Plugin::NAMESPACE . '\extensions\access-control-visitor-ip';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::ACCESS_CONTROL_VISITOR_IP,
        ];
    }

    protected function getExtensionSubfolder(): string
    {
        return 'access-control-visitor-ip';
    }
}
