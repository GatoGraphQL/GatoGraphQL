<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

class SettingsNormalizer implements SettingsNormalizerInterface
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
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
                ) {
                    /** @var mixed[] */
                    $arrayValue = $values[$name];
                    $values[$name] = array_map(
                        fn (int|string $value) => (int) $value,
                        $arrayValue
                    );
                }

                // Validate it is a valid value, or reset
                if (!$moduleResolver->isValidValue($module, $option, $values[$name] ?? null)) {
                    $values[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                }
            }
        }
        return $values;
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
