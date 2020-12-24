<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

interface UserSettingsManagerInterface
{
    /**
     * Timestamp of latest executed write to DB, concerning plugin activation,
     * module enabled/disabled, user settings updated
     *
     * @return integer
     */
    public function getTimestamp(): int;
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning plugin activation, module enabled/disabled, user settings updated
     */
    public function storeTimestamp(): void;
    /**
     * Remove the timestamp
     *
     * @return void
     */
    public function removeTimestamp(): void;
    public function hasSetting(string $item): bool;
    /**
     * No return type because it could be a bool/int/string
     *
     * @return mixed
     */
    public function getSetting(string $module, string $option);
    public function hasSetModuleEnabled(string $moduleID): bool;
    public function isModuleEnabled(string $moduleID): bool;
    public function setModuleEnabled(string $moduleID, bool $isEnabled): void;
    /**
     * @param array<string, bool> $moduleIDValues
     */
    public function setModulesEnabled(array $moduleIDValues): void;
}
