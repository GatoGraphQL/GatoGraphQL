<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemSettingsCategoryRegistryFacade;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\Settings\SettingsNormalizerInterface;
use PoPAPI\APIEndpoints\EndpointUtils;
use PoP\Root\App;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;

/**
 * Helper class with functions to set the configuration in PoP components.
 */
class PluginOptionsFormHandler
{
    /**
     * Cache the options after normalizing them
     *
     * @var array<string,array<string,array<string,mixed>|null>>
     */
    protected array $normalizedModuleOptionValuesCache = [];

    /**
     * Get the values from the form submitted to options.php, and normalize them
     *
     * @return array<string,mixed>
     */
    protected function getNormalizedModuleOptionValues(
        string $settingsCategory,
        string $module,
    ): array {
        if (($this->normalizedModuleOptionValuesCache[$settingsCategory][$module] ?? null) === null) {
            $instanceManager = SystemInstanceManagerFacade::getInstance();
            /** @var SettingsNormalizerInterface */
            $settingsNormalizer = $instanceManager->getInstance(SettingsNormalizerInterface::class);

            // Obtain the values from the POST and normalize them
            $value = $this->getSubmittedFormOptionValues($settingsCategory);

            /**
             * Important: call normalizeSettingsByModule instead of normalizeSettingsByCategory,
             * because there are settings that depend on other services, which are not initialized
             * in the system service.
             *
             * For instance, module SCHEMA_CONFIGURATION requires service
             * GraphQLSchemaConfigurationCustomPostType.
             *
             * If calling normalizeSettingsByCategory, this other module would
             * also be normalized, attempting to initialize these services,
             * and throwing an error.
             *
             * But just normalizing the modules that use `getURLPathSettingValue` and
             * `getCPTPermalinkBasePathSettingValue` (eg: GraphiQL client path, etc),
             * these ones currently have no other dependencies, and they do not fail.
             */
            $this->normalizedModuleOptionValuesCache[$settingsCategory][$module] = $settingsNormalizer->normalizeSettingsByModule($value, $module);
        }
        return $this->normalizedModuleOptionValuesCache[$settingsCategory][$module];
    }

    /**
     * Obtain the values from the POST
     *
     * @return array<string,mixed>
     */
    protected function getSubmittedFormOptionValues(
        string $settingsCategory,
    ): array {
        $settingsCategoryRegistry = SystemSettingsCategoryRegistryFacade::getInstance();
        $settingsCategoryResolver = $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory);
        $optionsFormName = $settingsCategoryResolver->getOptionsFormName($settingsCategory);
        return App::getRequest()->request->all()[$optionsFormName] ?? [];
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
    public function maybeOverrideValueFromForm(
        mixed $value,
        string $module,
        string $option,
    ): mixed {
        global $pagenow;
        if ($pagenow !== 'options.php') {
            return $value;
        }

        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $settingsCategoryRegistry = SystemSettingsCategoryRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $settingsCategory = $moduleResolver->getSettingsCategory($module);
        $formOrigin = App::request(SettingsMenuPage::FORM_ORIGIN);
        if ($formOrigin !== $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory)->getOptionsFormName($settingsCategory)) {
            return $value;
        }

        $value = $this->getNormalizedModuleOptionValues(
            $settingsCategory,
            $module,
        );
        // Return the specific value to this module/option
        $optionName = $moduleResolver->getSettingOptionName($module, $option);
        return $value[$optionName];
    }

    /**
     * Process the "URL path" option values
     */
    public function getURLPathSettingValue(
        string $value,
        string $module,
        string $option
    ): string {
        // If we are on options.php, use the value submitted to the form,
        // so it's updated before doing `add_rewrite_endpoint` and `flush_rewrite_rules`
        $value = $this->maybeOverrideValueFromForm($value, $module, $option);

        // Make sure the path has a "/" on both ends
        return EndpointUtils::slashURI($value);
    }

    /**
     * Process the "URL base path" option values
     */
    public function getCPTPermalinkBasePathSettingValue(
        string $value,
        string $module,
        string $option
    ): string {
        // If we are on options.php, use the value submitted to the form,
        // so it's updated before doing `add_rewrite_endpoint` and `flush_rewrite_rules`
        $value = $this->maybeOverrideValueFromForm($value, $module, $option);

        // Make sure the path does not have "/" on either end
        return trim($value, '/');
    }
}
