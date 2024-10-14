<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQLEngine\ModuleResolvers\ModuleResolverInterface as UpstreamModuleResolverInterface;

interface ModuleResolverInterface extends UpstreamModuleResolverInterface
{
    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool;

    /**
     * HTML Documentation for the module
     */
    public function getDocumentation(string $module): ?string;
}
