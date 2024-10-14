<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQLEngine\ModuleResolvers\AbstractModuleResolver as UpstreamAbstractModuleResolver;

abstract class AbstractModuleResolver extends UpstreamAbstractModuleResolver implements ModuleResolverInterface
{
    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool
    {
        return !empty($this->getDocumentation($module));
    }

    /**
     * HTML Documentation for the module
     */
    public function getDocumentation(string $module): ?string
    {
        return null;
    }
}
