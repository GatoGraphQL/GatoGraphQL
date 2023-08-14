<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface UserSettingsManagerInterface
{
    /**
     * Timestamp of latest executed write to DB, concerning plugin activation,
     * module enabled/disabled, user settings updated, to refresh the Service
     * Container
     */
    public function getContainerUniqueTimestamp(): string;
    /**
     * Timestamp of latest executed write to DB, concerning CPT entity created
     * or modified (such as Schema Configuration, ACL, etc), to refresh
     * the GraphQL schema
     */
    public function getOperationalUniqueTimestamp(): string;
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning plugin activation, module enabled/disabled, user settings updated,
     * to refresh the Service Container
     */
    public function storeContainerTimestamp(): void;
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning CPT entity created or modified (such as Schema Configuration,
     * ACL, etc), to refresh the GraphQL schema
     */
    public function storeOperationalTimestamp(): void;
    /**
     * Remove the timestamp
     */
    public function removeTimestamps(): void;
    public function hasSetting(string $module, string $option): bool;
    public function getSetting(string $module, string $option): mixed;
    public function setSetting(string $module, string $option, mixed $value): void;
    /**
     * @param array<string,mixed> $optionValues
     */
    public function setSettings(string $module, array $optionValues): void;
    public function hasSetModuleEnabled(string $moduleID): bool;
    public function isModuleEnabled(string $moduleID): bool;
    public function setModuleEnabled(string $moduleID, bool $isEnabled): void;
    /**
     * @param array<string,bool> $moduleIDValues
     */
    public function setModulesEnabled(array $moduleIDValues): void;
    public function storeEmptySettings(string $optionName): void;
}
