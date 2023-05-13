<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;

class ExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    private const GATO_GRAPHQL_PRO = Plugin::NAMESPACE . '\\extensions\\gato-graphql-pro';
    private const ACCESS_CONTROL_VISITOR_IP = Plugin::NAMESPACE . '\\extensions\\access-control-visitor-ip';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GATO_GRAPHQL_PRO,
            self::ACCESS_CONTROL_VISITOR_IP,
        ];
    }
}
