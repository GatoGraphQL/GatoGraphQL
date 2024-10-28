<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

trait OverrideToDisableModuleResolverTrait
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return false;
    }

    public function isHidden(string $module): bool
    {
        return true;
    }

    /**
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        return [];
    }
}
