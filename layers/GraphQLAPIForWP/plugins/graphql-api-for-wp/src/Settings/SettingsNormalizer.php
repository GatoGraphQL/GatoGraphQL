<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\Services\BasicServiceTrait;

class SettingsNormalizer implements SettingsNormalizerInterface
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string, string> $value All values submitted, each under its optionName as key
     * @return array<string, mixed> Normalized values
     */
    public function normalizeSettings(array $value): array
    {
        $items = $this->getAllSettingsItems();
        foreach ($items as $item) {
            $module = $item['module'];
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            foreach ($item['settings'] as $itemSetting) {
                $option = $itemSetting[Properties::INPUT] ?? null;
                // No option => it is a label
                if ($option === null) {
                    continue;
                }
                $type = $itemSetting[Properties::TYPE] ?? null;
                /**
                 * Cast type so PHPStan doesn't throw error
                 */
                $name = (string)$itemSetting[Properties::NAME];
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
                    (!$canBeEmpty && empty($value[$name]))
                    && $type != Properties::TYPE_BOOL
                    && !($type == Properties::TYPE_INT && $value[$name] == '0')
                ) {
                    $value[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                } elseif ($type == Properties::TYPE_BOOL) {
                    $value[$name] = !empty($value[$name]);
                } elseif ($type == Properties::TYPE_INT) {
                    $value[$name] = (int) $value[$name];
                    // If the value is below its minimum, reset to the default one
                    $minNumber = $itemSetting[Properties::MIN_NUMBER] ?? null;
                    if (!is_null($minNumber) && $value[$name] < $minNumber) {
                        $value[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                    }
                } elseif (
                    $type == Properties::TYPE_ARRAY
                    && is_string($value[$name])
                ) {
                    // Check if the type is array, but it's delivered as a string via a textarea
                    $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
                    if (empty($possibleValues)) {
                        $value[$name] = explode("\n", $value[$name]);
                    }
                }

                // Validate it is a valid value, or reset
                if (!$moduleResolver->isValidValue($module, $option, $value[$name])) {
                    $value[$name] = $moduleResolver->getSettingsDefaultValue($module, $option);
                }
            }
        }
        return $value;
    }

    /**
     * Return all the modules with settings
     *
     * @return array<array> Each item is an array of prop => value
     */
    public function getAllSettingsItems(): array
    {
        $items = [];
        $modules = $this->getModuleRegistry()->getAllModules(true, true, false);
        foreach ($modules as $module) {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            $items[] = [
                'module' => $module,
                'id' => $moduleResolver->getID($module),
                'name' => $moduleResolver->getName($module),
                'settings' => $moduleResolver->getSettings($module),
            ];
        }
        return $items;
    }
}
