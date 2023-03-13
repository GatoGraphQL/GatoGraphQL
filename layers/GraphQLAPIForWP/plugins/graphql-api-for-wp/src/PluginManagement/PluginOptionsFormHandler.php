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
     * @var array<string,array<string,mixed>|null>
     */
    protected array $normalizedOptionValuesCache = [];

    /**
     * Get the values from the form submitted to options.php, and normalize them
     *
     * @return array<string,mixed>
     */
    public function getNormalizedOptionValues(string $settingsCategory): array
    {
        if (($this->normalizedOptionValuesCache[$settingsCategory] ?? null) === null) {
            $instanceManager = SystemInstanceManagerFacade::getInstance();
            $settingsCategoryRegistry = SystemSettingsCategoryRegistryFacade::getInstance();
            $settingsCategoryResolver = $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory);
            $optionsFormName = $settingsCategoryResolver->getOptionsFormName($settingsCategory);

            /** @var SettingsNormalizerInterface */
            $settingsNormalizer = $instanceManager->getInstance(SettingsNormalizerInterface::class);
            // Obtain the values from the POST and normalize them
            $value = App::getRequest()->request->all()[$optionsFormName] ?? [];
            $this->normalizedOptionValuesCache[$settingsCategory] = $settingsNormalizer->normalizeSettings($value, $settingsCategory);
        }
        return $this->normalizedOptionValuesCache[$settingsCategory];
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
    public function maybeOverrideValueFromForm(mixed $value, string $module, string $option): mixed
    {
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

        $value = $this->getNormalizedOptionValues($settingsCategory);
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
