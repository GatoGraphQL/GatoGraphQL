<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Enums\WordPressAIConnectorProvider;

use function get_option;

class WordPressStaticHelpers
{
    /**
     * Resolve the API key stored by the WordPress 7.0+ AI Connector for the
     * given provider, using the same lookup order as WP core:
     *
     *   1. Environment variable `{PROVIDER}_API_KEY`
     *   2. PHP constant `{PROVIDER}_API_KEY`
     *   3. Database option `connectors_ai_{provider}_api_key`
     */
    public static function getWPConnectorAIProviderAPIKey(WordPressAIConnectorProvider $provider): ?string
    {
        // Detect WP 7.0+: `wp_get_connectors()` is the public entry-point for the Connectors API.
        if (!function_exists('wp_get_connectors')) {
            return null;
        }

        $providerID = $provider->value;
        $envVarName = strtoupper($providerID) . '_API_KEY';

        $envValue = getenv($envVarName);
        if ($envValue !== false && $envValue !== '') {
            return $envValue;
        }

        if (defined($envVarName)) {
            $constValue = constant($envVarName);
            if (is_string($constValue) && $constValue !== '') {
                return $constValue;
            }
        }

        $dbValue = get_option('connectors_ai_' . $providerID . '_api_key', '');
        if (is_string($dbValue) && $dbValue !== '') {
            return $dbValue;
        }

        return null;
    }
}
