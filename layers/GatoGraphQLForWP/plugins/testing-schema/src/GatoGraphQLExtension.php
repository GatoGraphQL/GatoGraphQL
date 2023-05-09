<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema;

use PoP\Root\Module\ModuleInterface;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractExtension;

class GatoGraphQLExtension extends AbstractExtension
{
    /**
     * Plugin's namespace
     */
    public final const NAMESPACE = __NAMESPACE__;

    /**
     * Add Module classes to be initialized
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class to initialize
     */
    protected function getModuleClassesToInitialize(): array
    {
        return [
            Module::class,
        ];
    }
}
