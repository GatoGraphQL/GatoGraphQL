<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Enums\URLPathSettingType;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\Services\AbstractBasicService;

use function __;
use function add_settings_error;
use function esc_html;
use function function_exists;
use function get_settings_errors;
use function is_string;
use function sprintf;
use function trim;

class SettingsNormalizer extends AbstractBasicService implements SettingsNormalizerInterface
{
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?URLPathSettingValidatorInterface $urlPathSettingValidator = null;
    private ?UserSettingsManagerInterface $userSettingsManager = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final protected function getURLPathSettingValidator(): URLPathSettingValidatorInterface
    {
        if ($this->urlPathSettingValidator === null) {
            /** @var URLPathSettingValidatorInterface */
            $urlPathSettingValidator = $this->instanceManager->getInstance(URLPathSettingValidatorInterface::class);
            $this->urlPathSettingValidator = $urlPathSettingValidator;
        }
        return $this->urlPathSettingValidator;
    }
    final protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }

    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string,string> $values All values submitted, each under its optionName as key
     * @return array<string,mixed> Normalized values
     */
    public function normalizeSettingsByCategory(
        array $values,
        string $settingsCategory,
    ): array {
        $settingsItems = $this->getAllSettingsItems($settingsCategory);
        return $this->normalizeSettings($values, $settingsItems);
    }

    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string,string> $values All values submitted, each under its optionName as key
     * @return array<string,mixed> Normalized values
     */
    public function normalizeSettingsByModule(
        array $values,
        string $module,
    ): array {
        $settingsItems = [$this->getSettingsItem($module)];
        return $this->normalizeSettings($values, $settingsItems);
    }

    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string,string> $values All values submitted, each under its optionName as key
     * @param array<array<string, mixed>> $settingsItems Each item is an array of prop => value
     * @return array<string,mixed> Normalized values
     */
    protected function normalizeSettings(
        array $values,
        array $settingsItems,
    ): array {
        $moduleRegistry = $this->getModuleRegistry();

        /**
         * All form fields will be provided via the Settings form.
         * If they are not, then this method has been invoked by
         * executing `maybeStoreEmptySettings` or
         * `$userSettingsManager->storeEmptySettings($option)`
         * in `resetOptions`, where an empty array is passed
         * to `update_option`.
         *
         * In that case, fill it in with the default values.
         *
         * @see layers/GatoGraphQLForWP/plugins/gatographql/src/Services/MenuPages/SettingsMenuPage.php
         */
        if ($values === []) {
            foreach ($settingsItems as $item) {
                $module = $item['module'];
                $moduleResolver = $moduleRegistry->getModuleResolver($module);
                foreach ($item['settings'] as $itemSetting) {
                    $option = $itemSetting[Properties::INPUT] ?? null;
                    // No option => it is a label
                    if ($option === null) {
                        continue;
                    }
                    /** @var string */
                    $name = $itemSetting[Properties::NAME];
                    $values[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                }
            }
            return $values;
        }

        foreach ($settingsItems as $item) {
            $module = $item['module'];
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            foreach ($item['settings'] as $itemSetting) {
                $option = $itemSetting[Properties::INPUT] ?? null;
                // No option => it is a label
                if ($option === null) {
                    continue;
                }
                $type = $itemSetting[Properties::TYPE] ?? null;
                $subtype = $itemSetting[Properties::SUBTYPE] ?? null;
                /** @var string */
                $name = $itemSetting[Properties::NAME];
                /** @var bool */
                $canBeEmpty = $itemSetting[Properties::CAN_BE_EMPTY] ?? false;

                /**
                 * If the input is empty, replace with the default
                 * It can't be empty, because that could be equivalent
                 * to disabling the module, which is done
                 * from the Modules page, not from Settings.
                 * Ignore for bool since empty means `false` (tackled below)
                 * For int, "0" is valid, it must not be considered empty
                 */
                if (
                    (!$canBeEmpty && empty($values[$name]))
                    && $type !== Properties::TYPE_BOOL
                    && !($type === Properties::TYPE_INT && ($values[$name] ?? null) == '0')
                ) {
                    $values[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                } elseif ($type === Properties::TYPE_BOOL) {
                    $values[$name] = !empty($values[$name]);
                } elseif ($type === Properties::TYPE_INT) {
                    $values[$name] = (int) ($values[$name] ?? null);
                    // If the value is below its minimum, reset to the default one
                    $minNumber = $itemSetting[Properties::MIN_NUMBER] ?? null;
                    if ($minNumber !== null && $values[$name] < $minNumber) {
                        $values[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                    }
                } elseif (
                    $type === Properties::TYPE_ARRAY
                    && is_string($values[$name] ?? null)
                ) {
                    // Check if the type is array, but it's delivered as a string via a textarea
                    $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
                    if (empty($possibleValues)) {
                        $values[$name] = explode("\n", $values[$name]);
                    }
                } elseif (
                    $type === Properties::TYPE_ARRAY
                    && $canBeEmpty
                    && ($values[$name] ?? null) === null
                ) {
                    $values[$name] = [];
                } elseif (
                    $type === Properties::TYPE_ARRAY
                    && $subtype === Properties::TYPE_INT
                    && is_array($values[$name] ?? null)
                ) {
                    /** @var mixed[] */
                    $arrayValue = $values[$name];
                    $values[$name] = array_map(
                        fn (mixed $value) => (int) $value,
                        $arrayValue
                    );
                }

                // Validate it is a valid value, or reset
                if (!$moduleResolver->isValidValue($module, $option, $values[$name] ?? null)) {
                    $values[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                }

                $urlPathSettingType = $moduleResolver->getURLPathSettingOptions($module)[$option] ?? null;
                if ($urlPathSettingType !== null && is_string($values[$name] ?? null)) {
                    /** @var string */
                    $moduleName = $item['name'];
                    /** @var string */
                    $title = $itemSetting[Properties::TITLE] ?? $option;
                    $values[$name] = $this->validateURLPathSettingValue(
                        $values,
                        $module,
                        $option,
                        $urlPathSettingType,
                        sprintf(
                            __('"%1$s" in "%2$s"', 'gatographql'),
                            $title,
                            $moduleName
                        ),
                    );
                }
            }
        }
        return $values;
    }

    /**
     * Validate the value of an option that modifies permalinks
     * (such as the path of the single endpoint). If it is not
     * valid, keep the previous value, and show an error.
     *
     * A value that has not been modified is accepted as it is,
     * so that saving the Settings never changes a path that
     * was already in use.
     *
     * @param array<string,mixed> $values All values submitted, each under its optionName as key
     */
    protected function validateURLPathSettingValue(
        array $values,
        string $module,
        string $option,
        URLPathSettingType $urlPathSettingType,
        string $settingLabel,
    ): string {
        $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        $name = $moduleResolver->getSettingOptionName($module, $option);
        /** @var string */
        $value = $values[$name];
        $value = trim($value);

        $urlPathSettingValidator = $this->getURLPathSettingValidator();
        $urlPath = $urlPathSettingValidator->normalizeURLPath($value);
        $previousValue = $this->getStoredURLPathSettingValue($module, $option);
        if ($urlPath === $urlPathSettingValidator->normalizeURLPath($previousValue)) {
            return $value;
        }

        $error = $urlPathSettingValidator->getURLPathError($urlPath, $urlPathSettingType)
            ?? $this->getURLPathSettingClashError($values, $module, $option, $urlPathSettingType, $urlPath);
        if ($error !== null) {
            $this->addSettingsError(
                $name,
                sprintf(
                    __('Option %1$s was not saved, as %2$s. It keeps value "%3$s".', 'gatographql'),
                    $settingLabel,
                    esc_html($error),
                    esc_html($previousValue)
                ),
                'error'
            );
            return $previousValue;
        }

        if ($urlPathSettingType === URLPathSettingType::ENDPOINT) {
            $page = $urlPathSettingValidator->getPageUnderURLPath($urlPath);
            if ($page !== null) {
                $this->addSettingsError(
                    $name,
                    sprintf(
                        __('Option %1$s: page "%2$s" has path "%3$s" too, so it can\'t be accessed anymore.', 'gatographql'),
                        $settingLabel,
                        esc_html($page->post_title),
                        esc_html($urlPath)
                    ),
                    'warning'
                );
            }
        }

        return $value;
    }

    protected function getStoredURLPathSettingValue(string $module, string $option): string
    {
        $value = $this->getUserSettingsManager()->getSetting($module, $option);
        if (is_string($value) && trim($value) !== '') {
            return $value;
        }
        /** @var string */
        return $this->getModuleRegistry()->getModuleResolver($module)->getSettingsDefaultValue($module, $option);
    }

    /**
     * Two endpoints under the same path (or two custom post types
     * under the same base slug) make one of them unreachable.
     *
     * The other option's value is the one submitted in the
     * form, or the stored one if it is in another form.
     *
     * @param array<string,mixed> $values All values submitted, each under its optionName as key
     */
    protected function getURLPathSettingClashError(
        array $values,
        string $module,
        string $option,
        URLPathSettingType $urlPathSettingType,
        string $urlPath,
    ): ?string {
        $moduleRegistry = $this->getModuleRegistry();
        $urlPathSettingValidator = $this->getURLPathSettingValidator();
        foreach ($moduleRegistry->getAllModules() as $otherModule) {
            $otherModuleResolver = $moduleRegistry->getModuleResolver($otherModule);
            foreach ($otherModuleResolver->getURLPathSettingOptions($otherModule) as $otherOption => $otherURLPathSettingType) {
                if (
                    $otherURLPathSettingType !== $urlPathSettingType
                    || ($otherModule === $module && $otherOption === $option)
                    || !$moduleRegistry->isModuleEnabled($otherModule)
                ) {
                    continue;
                }
                $otherName = $otherModuleResolver->getSettingOptionName($otherModule, $otherOption);
                $otherValue = $values[$otherName] ?? null;
                if (!is_string($otherValue)) {
                    $otherValue = $this->getStoredURLPathSettingValue($otherModule, $otherOption);
                } elseif (trim($otherValue) === '') {
                    /** @var string */
                    $otherValue = $otherModuleResolver->getSettingsDefaultValue($otherModule, $otherOption);
                }
                if ($urlPathSettingValidator->normalizeURLPath($otherValue) !== $urlPath) {
                    continue;
                }
                return sprintf(
                    __('it is already used by "%s"', 'gatographql'),
                    $otherModuleResolver->getName($otherModule)
                );
            }
        }
        return null;
    }

    /**
     * Show the message in the Settings page. It is added
     * only when saving the Settings in options.php (where
     * function `add_settings_error` is loaded), and only once,
     * as the Settings are normalized more than once.
     */
    protected function addSettingsError(string $name, string $message, string $type): void
    {
        if (!function_exists('add_settings_error')) {
            return;
        }
        foreach (get_settings_errors($name) as $settingsError) {
            if ($settingsError['message'] === $message) {
                return;
            }
        }
        add_settings_error($name, $name, $message, $type);
    }

    /**
     * Normalize the form values for a specific module.
     *
     * This method sets the previous values properly when
     * called from the REST API (eg: when executing Integration Tests)
     * as the previousValue for some Settings option could be non-existent,
     * and it must be overridden/removed.
     *
     * @param array<string,string> $values All values submitted, each under its optionName as key
     * @return array<string,mixed> Normalized values
     */
    public function normalizeSettingsForRESTAPIController(string $module, array $values): array
    {
        $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);

        // Obtain the settingsOptionName for each option
        $normalizedOptionValues = [];
        foreach ($values as $option => $value) {
            $settingsOptionName = $moduleResolver->getSettingOptionName($module, $option);
            $normalizedOptionValues[$settingsOptionName] = $value;
        }
        // Normalize it
        $normalizedOptionValues = $this->normalizeSettingsByCategory(
            $normalizedOptionValues,
            $moduleResolver->getSettingsCategory($module)
        );

        // Transform back
        foreach ($values as $option => $value) {
            $settingsOptionName = $moduleResolver->getSettingOptionName($module, $option);
            $values[$option] = $normalizedOptionValues[$settingsOptionName];
        }

        return $values;
    }

    /**
     * Return all the modules with settings
     *
     * @return array<array<string,mixed>> Each item is an array of prop => value
     */
    public function getAllSettingsItems(?string $settingsCategory = null): array
    {
        $moduleRegistry = $this->getModuleRegistry();
        $settingsItems = [];
        $modules = $moduleRegistry->getAllModules(true, true, false, true, $settingsCategory);
        foreach ($modules as $module) {
            $settingsItems[] = $this->getSettingsItem($module);
        }
        return $settingsItems;
    }

    /**
     * Return all the modules with settings
     *
     * @return array<string,mixed> Array of prop => value
     */
    protected function getSettingsItem(string $module): array
    {
        $moduleRegistry = $this->getModuleRegistry();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        return [
            'module' => $module,
            'id' => $moduleResolver->getID($module),
            'name' => $moduleResolver->getName($module),
            'settings' => $moduleResolver->getSettings($module),
            'settings-category' => $moduleResolver->getSettingsCategory($module),
        ];
    }
}
