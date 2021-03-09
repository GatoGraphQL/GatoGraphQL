<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;
use PoP\Root\Environment as RootEnvironment;

/**
 * The cache modules have different behavior depending on the environment:
 * - "development": visible, disabled by default
 * - "production": hidden, enabled by default
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
abstract class AbstractCacheFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::PERFORMANCE;
    }

    /**
     * Allow to change the behavior based on the environment
     *
     * @return boolean
     */
    protected function enabledEnvironmentBasedBehavior(): bool
    {
        return false;
    }

    public function isHidden(string $module): bool
    {
        if ($this->enabledEnvironmentBasedBehavior()) {
            return RootEnvironment::isApplicationEnvironmentProd();
        }
        return parent::isHidden($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        if ($this->enabledEnvironmentBasedBehavior()) {
            return RootEnvironment::isApplicationEnvironmentProd();
        }
        return parent::isEnabledByDefault($module);
    }
}
