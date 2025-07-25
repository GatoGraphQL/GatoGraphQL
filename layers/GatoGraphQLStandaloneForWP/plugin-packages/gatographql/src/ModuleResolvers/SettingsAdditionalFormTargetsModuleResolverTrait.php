<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

trait SettingsAdditionalFormTargetsModuleResolverTrait
{
    /**
     * @param array<array<string,mixed>> $settings
     * @param array<string> $additionalFormTargets
     * @return array<array<string,mixed>>
     */
    protected function addAdditionalFormTargetsToSettingsItems(array $settings, array $additionalFormTargets): array
    {
        // Also add the target to the settings
        foreach ($settings as &$setting) {
            $setting[Properties::ADDITIONAL_FORM_TARGETS] = [
                ...($setting[Properties::ADDITIONAL_FORM_TARGETS] ?? []),
                ...$additionalFormTargets,
            ];
        }
        return $settings;
    }
}
