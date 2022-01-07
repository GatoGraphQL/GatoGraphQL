<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use PoP\APIEndpoints\EndpointUtils;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

/**
 * Helper class with functions to set the configuration in PoP components.
 */
class PluginManagementHelpers
{
    /**
     * Cache the options after normalizing them
     *
     * @var array<string, mixed>|null
     */
    protected static ?array $normalizedOptionValuesCache = null;

    /**
     * Get the values from the form submitted to options.php, and normalize them
     *
     * @return array<string, mixed>
     */
    public static function getNormalizedOptionValues(): array
    {
        if (self::$normalizedOptionValuesCache === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var SettingsMenuPage
             */
            $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
            // Obtain the values from the POST and normalize them
            $value = $_POST[SettingsMenuPage::SETTINGS_FIELD] ?? [];
            self::$normalizedOptionValuesCache = $settingsMenuPage->normalizeSettings($value);
        }
        return self::$normalizedOptionValuesCache;
    }

    /**
     * If we are in options.php, already set the new slugs in the hook,
     * so that the EndpointHandler's `addRewriteEndpoints` (executed on `init`)
     * adds the rewrite with the new slug, which will be persisted on
     * flushing the rewrite rules
     *
     * Hidden input "form-origin" is used to only execute for this plugin,
     * since options.php is used everywhere, including WP core and other plugins.
     * Otherwise, it may thrown an exception!
     */
    public static function maybeOverrideValueFromForm(mixed $value, string $module, string $option): mixed
    {
        global $pagenow;
        if (
            $pagenow == 'options.php'
            && isset($_REQUEST[SettingsMenuPage::FORM_ORIGIN])
            && $_REQUEST[SettingsMenuPage::FORM_ORIGIN] == SettingsMenuPage::SETTINGS_FIELD
        ) {
            $value = self::getNormalizedOptionValues();
            // Return the specific value to this module/option
            $moduleRegistry = SystemModuleRegistryFacade::getInstance();
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $optionName = $moduleResolver->getSettingOptionName($module, $option);
            return $value[$optionName];
        }
        return $value;
    }

    /**
     * Process the "URL path" option values
     */
    public static function getURLPathSettingValue(
        string $value,
        string $module,
        string $option
    ): string {
        // If we are on options.php, use the value submitted to the form,
        // so it's updated before doing `add_rewrite_endpoint` and `flush_rewrite_rules`
        $value = self::maybeOverrideValueFromForm($value, $module, $option);

        // Make sure the path has a "/" on both ends
        return EndpointUtils::slashURI($value);
    }

    /**
     * Process the "URL base path" option values
     */
    public static function getCPTPermalinkBasePathSettingValue(
        string $value,
        string $module,
        string $option
    ): string {
        // If we are on options.php, use the value submitted to the form,
        // so it's updated before doing `add_rewrite_endpoint` and `flush_rewrite_rules`
        $value = self::maybeOverrideValueFromForm($value, $module, $option);

        // Make sure the path does not have "/" on either end
        return trim($value, '/');
    }
}
