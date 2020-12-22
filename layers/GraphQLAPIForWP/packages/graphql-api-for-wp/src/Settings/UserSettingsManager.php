<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Settings\Options;

class UserSettingsManager implements UserSettingsManagerInterface
{
    /**
     * Cache the values in memory
     *
     * @var array<string, array>
     */
    protected array $options = [];

    /**
     * Timestamp of latest executed write to DB, concerning plugin activation,
     * module enabled/disabled, user settings updated
     */
    public function getTimestamp(): int
    {
        return (int) \get_option(Options::TIMESTAMP, 0);
    }
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning plugin activation, module enabled/disabled, user settings updated
     */
    public function storeTimestamp(): void
    {
        \update_option(Options::TIMESTAMP, time());
    }
    /**
     * Remove the timestamp
     */
    public function removeTimestamp(): void
    {
        \delete_option(Options::TIMESTAMP);
    }

    public function hasSetting(string $item): bool
    {
        return $this->hasItem(Options::SETTINGS, $item);
    }

    /**
     * No return type because it could be a bool/int/string
     *
     * @return mixed
     */
    public function getSetting(string $module, string $option)
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);

        // If the item is saved in the DB, retrieve it
        $item = $moduleResolver->getSettingOptionName($module, $option);
        if ($this->hasItem(Options::SETTINGS, $item)) {
            return $this->getItem(Options::SETTINGS, $item);
        }

        // Otherwise, return the default value
        return $moduleResolver->getSettingsDefaultValue($module, $option);
    }

    public function hasSetModuleEnabled(string $moduleID): bool
    {
        return $this->hasItem(Options::MODULES, $moduleID);
    }

    public function isModuleEnabled(string $moduleID): bool
    {
        return (bool) $this->getItem(Options::MODULES, $moduleID);
    }

    public function setModuleEnabled(string $moduleID, bool $isEnabled): void
    {
        $this->storeItem(Options::MODULES, $moduleID, $isEnabled);

        // Update the timestamp
        $this->storeTimestamp();
    }

    /**
     * @param array<string, bool> $moduleIDValues
     */
    public function setModulesEnabled(array $moduleIDValues): void
    {
        $this->storeItems(Options::MODULES, $moduleIDValues);

        // Update the timestamp
        $this->storeTimestamp();
    }

    /**
     * Get the stored value for the option under the group
     *
     * @return mixed
     */
    protected function getItem(string $optionName, string $item)
    {
        $this->maybeLoadOptions($optionName);
        return $this->options[$optionName][$item];
    }

    /**
     * Is there a stored value for the option under the group
     */
    protected function hasItem(string $optionName, string $item): bool
    {
        $this->maybeLoadOptions($optionName);
        return isset($this->options[$optionName][$item]);
    }

    /**
     * Load the options from the DB
     */
    protected function maybeLoadOptions(string $optionName): void
    {
        // Lazy load the options
        if (!isset($this->options[$optionName])) {
            $this->options[$optionName] = \get_option($optionName, []);
        }
    }

    /**
     * Store the options in the DB
     *
     * @param mixed $value
     */
    protected function storeItem(string $optionName, string $item, $value): void
    {
        $this->storeItems($optionName, [$item => $value]);
    }

    /**
     * Store the options in the DB
     *
     * @param array<string, mixed> $itemValues
     */
    protected function storeItems(string $optionName, array $itemValues): void
    {
        $this->maybeLoadOptions($optionName);
        // Change the values of the items
        $this->options[$optionName] = array_merge(
            $this->options[$optionName],
            $itemValues
        );
        // Save to the DB
        \update_option($optionName, $this->options[$optionName]);
    }
}
