<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface TimestampSettingsManagerInterface
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
}
