<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;

class UserSettingsManager implements UserSettingsManagerInterface
{
    private const TIMESTAMP_CONTAINER = 'container';
    private const TIMESTAMP_OPERATIONAL = 'operational';

    /**
     * Cache the values in memory
     *
     * @var array<string, array>
     */
    protected array $options = [];

    /**
     * Timestamp of latest executed write to DB, concerning plugin activation,
     * module enabled/disabled, user settings updated.
     *
     * If there is not timestamp yet, then we just installed the plugin.
     *
     * In that case, we must return a random `time()` timestamp and not
     * a fixed value such as `0`, because the service container
     * will be generated on each interaction with WordPress,
     * including WP-CLI.
     *
     * Using `0` as the default value, when installing the plugin
     * and an extension via WP-CLI (before accessing wp-admin)
     * it will throw errors, because after installing the main plugin
     * the container cache is generated and cached with timestamp `0`,
     * and it would be loaded again when installing the extension,
     * however the cache does not contain the services from the extension.
     *
     * By providing `time()`, the cached service container is always
     * a one-time-use before accessing the wp-admin and
     * having a new timestamp generated via `purgeContainer`.
     */
    protected function getTimestamp(string $key): int
    {
        $timestamps = \get_option(Options::TIMESTAMPS, [$key => time()]);
        return (int) $timestamps[$key];
    }
    /**
     * Static timestamp, reflecting when the service container has been regenerated.
     * Should change not so often
     */
    public function getContainerTimestamp(): int
    {
        return $this->getTimestamp(self::TIMESTAMP_CONTAINER);
    }
    /**
     * Dynamic timestamp, reflecting when new entities modifying the schema are
     * added to the DB. Can change often
     */
    public function getOperationalTimestamp(): int
    {
        return $this->getTimestamp(self::TIMESTAMP_OPERATIONAL);
    }
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning plugin activation, module enabled/disabled, user settings updated,
     * to refresh the Service Container.
     *
     * When this value is updated, the "operational" timestamp is also updated.
     */
    public function storeContainerTimestamp(): void
    {
        $time = time();
        $timestamps = [
            self::TIMESTAMP_CONTAINER => $time,
            self::TIMESTAMP_OPERATIONAL => $time,
        ];
        \update_option(Options::TIMESTAMPS, $timestamps);
    }
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning CPT entity created or modified (such as Schema Configuration,
     * ACL, etc), to refresh the GraphQL schema
     */
    public function storeOperationalTimestamp(): void
    {
        $timestamps = [
            self::TIMESTAMP_CONTAINER => $this->getContainerTimestamp(),
            self::TIMESTAMP_OPERATIONAL => time(),
        ];
        \update_option(Options::TIMESTAMPS, $timestamps);
    }
    /**
     * Remove the timestamp
     */
    public function removeTimestamps(): void
    {
        \delete_option(Options::TIMESTAMPS);
    }

    public function hasSetting(string $module, string $option): bool
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $item = $moduleResolver->getSettingOptionName($module, $option);
        return $this->hasItem(Options::SETTINGS, $item);
    }

    public function getSetting(string $module, string $option): mixed
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
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
        $this->storeContainerTimestamp();
    }

    /**
     * @param array<string, bool> $moduleIDValues
     */
    public function setModulesEnabled(array $moduleIDValues): void
    {
        $this->storeItems(Options::MODULES, $moduleIDValues);

        // Update the timestamp
        $this->storeContainerTimestamp();
    }

    /**
     * Get the stored value for the option under the group
     */
    protected function getItem(string $optionName, string $item): mixed
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
     */
    protected function storeItem(string $optionName, string $item, mixed $value): void
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
