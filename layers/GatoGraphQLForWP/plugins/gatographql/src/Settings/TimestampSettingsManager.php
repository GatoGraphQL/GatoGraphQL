<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;

use function delete_option;
use function get_option;
use function update_option;
use function uniqid;

class TimestampSettingsManager implements TimestampSettingsManagerInterface
{
    private const TIMESTAMP_CONTAINER = 'container';
    private const TIMESTAMP_OPERATIONAL = 'operational';

    /**
     * Cache the values in memory
     *
     * @var array<string,array<string,mixed>>
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
    protected function getOptionUniqueTimestamp(string $key): string
    {
        $timestamps = get_option($this->namespaceOption(Options::TIMESTAMPS), [$key => $this->getUniqueIdentifier()]);
        return $timestamps[$key];
    }

    protected function namespaceOption(string $option): string
    {
        $optionNamespacer = OptionNamespacerFacade::getInstance();
        return $optionNamespacer->namespaceOption($option);
    }

    /**
     * Add a random number to `time()` to make it truly unique,
     * as to avoid a bug when 2 requests with different schema
     * configuration come in at the same time (i.e. with same `time()`).
     */
    protected function getUniqueIdentifier(): string
    {
        return uniqid();
    }
    /**
     * Static timestamp, reflecting when the service container has been regenerated.
     * Should change not so often
     */
    public function getContainerUniqueTimestamp(): string
    {
        return $this->getOptionUniqueTimestamp(self::TIMESTAMP_CONTAINER);
    }
    /**
     * Dynamic timestamp, reflecting when new entities modifying the schema are
     * added to the DB. Can change often
     */
    public function getOperationalUniqueTimestamp(): string
    {
        return $this->getOptionUniqueTimestamp(self::TIMESTAMP_OPERATIONAL);
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
        $uniqueID = $this->getUniqueIdentifier();
        $timestamps = [
            self::TIMESTAMP_CONTAINER => $uniqueID,
            self::TIMESTAMP_OPERATIONAL => $uniqueID,
        ];
        update_option($this->namespaceOption(Options::TIMESTAMPS), $timestamps);
    }
    /**
     * Store the current time to indicate the latest executed write to DB,
     * concerning CPT entity created or modified (such as Schema Configuration,
     * ACL, etc), to refresh the GraphQL schema
     */
    public function storeOperationalTimestamp(): void
    {
        $timestamps = [
            self::TIMESTAMP_CONTAINER => $this->getContainerUniqueTimestamp(),
            self::TIMESTAMP_OPERATIONAL => $this->getUniqueIdentifier(),
        ];
        update_option($this->namespaceOption(Options::TIMESTAMPS), $timestamps);
    }
    /**
     * Remove the timestamp
     */
    public function removeTimestamps(): void
    {
        delete_option($this->namespaceOption(Options::TIMESTAMPS));
    }
}
