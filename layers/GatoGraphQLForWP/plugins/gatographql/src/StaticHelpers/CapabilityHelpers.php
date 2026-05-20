<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use function wp_roles;

class CapabilityHelpers
{
    public static function getSettingsMenuPageRequiredCapability(): string
    {
        return 'manage_options';
    }

    /**
     * Enumerate every capability declared by any registered WordPress
     * role, so settings UIs can render a select instead of a free-form
     * input (typos otherwise silently turn into "deny everyone").
     *
     * @param string|null $emptyOptionLabel If non-null, the resulting map
     *                                       is prefixed with `'' => $emptyOptionLabel`
     *                                       so the form can encode "no
     *                                       capability required" (or the
     *                                       caller's chosen synonym).
     *                                       Pass null to omit the empty
     *                                       option entirely.
     * @return array<string,string>
     */
    public static function getAvailableCapabilities(?string $emptyOptionLabel = null): array
    {
        $values = [];
        if ($emptyOptionLabel !== null) {
            $values[''] = $emptyOptionLabel;
        }
        if (function_exists('wp_roles')) {
            $capabilityNames = [];
            foreach (wp_roles()->roles as $role) {
                if (!is_array($role['capabilities'] ?? null)) {
                    continue;
                }
                foreach (array_keys($role['capabilities']) as $capabilityName) {
                    if (!is_string($capabilityName) || $capabilityName === '') {
                        continue;
                    }
                    $capabilityNames[$capabilityName] = true;
                }
            }
            $capabilityNames = array_keys($capabilityNames);
            sort($capabilityNames);
            foreach ($capabilityNames as $capabilityName) {
                $values[$capabilityName] = $capabilityName;
            }
        }
        // Ensure the secure default is always selectable even if no role
        // currently declares it (e.g. a stripped-down install).
        if (!isset($values['manage_options'])) {
            $values['manage_options'] = 'manage_options';
        }
        return $values;
    }
}
