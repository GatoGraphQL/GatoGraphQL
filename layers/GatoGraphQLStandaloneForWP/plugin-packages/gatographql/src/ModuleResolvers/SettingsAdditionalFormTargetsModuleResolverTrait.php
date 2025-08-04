<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

trait SettingsAdditionalFormTargetsModuleResolverTrait
{
    /**
     * @param array<array<string,mixed>> $settings
     * @param array<string> $formTargets
     * @return array<array<string,mixed>>
     */
    protected function addFormTargetsToSettingsItems(array $settings, array $formTargets): array
    {
        // Also add the target to the settings
        foreach ($settings as &$setting) {
            $setting[Properties::FORM_TARGETS] = [
                ...($setting[Properties::FORM_TARGETS] ?? []),
                ...$formTargets,
            ];
        }
        return $settings;
    }
}
