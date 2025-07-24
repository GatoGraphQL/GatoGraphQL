<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

trait SettingsAdditionalTargetModuleResolverTrait
{
    /**
     * @param array<array<string,mixed>> $settings
     * @param array<string> $additionalTargets
     * @return array<array<string,mixed>>
     */
    protected function addAdditionalTargetToSettingsItems(array $settings, array $additionalTargets): array
    {
        // Also add the target to the settings
        foreach ($settings as &$setting) {
            $setting[Properties::ADDITIONAL_TARGETS] = [
                ...($setting[Properties::ADDITIONAL_TARGETS] ?? []),
                ...$additionalTargets,
            ];
        }
        return $settings;
    }
}
