<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\JSONDataOptionSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;
use GatoGraphQL\GatoGraphQL\Facades\TimestampSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Facades\TransientSettingsManagerFacade;

use function uniqid;

abstract class AbstractSettingsManager
{
    private ?TimestampSettingsManagerInterface $timestampSettingsManager = null;
    private ?TransientSettingsManagerInterface $transientSettingsManager = null;
    private ?JSONDataOptionSettingsManagerInterface $jsonDataOptionSettingsManager = null;
    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getTimestampSettingsManager(): TimestampSettingsManagerInterface
    {
        return $this->timestampSettingsManager ??= TimestampSettingsManagerFacade::getInstance();
    }
    final protected function getTransientSettingsManager(): TransientSettingsManagerInterface
    {
        return $this->transientSettingsManager ??= TransientSettingsManagerFacade::getInstance();
    }
    final protected function getJSONDataOptionSettingsManager(): JSONDataOptionSettingsManagerInterface
    {
        return $this->jsonDataOptionSettingsManager ??= JSONDataOptionSettingsManagerFacade::getInstance();
    }
    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        return $this->optionNamespacer ??= OptionNamespacerFacade::getInstance();
    }

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
        /** @var string */
        $timestamp = $this->getTimestampSettingsManager()->getTimestamp($key, $this->getUniqueIdentifier());
        return $timestamp;
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

    protected function getTimestamp(string $timestampName): ?int
    {
        $timestamp = $this->getTimestampSettingsManager()->getTimestamp($timestampName);
        if ($timestamp === null) {
            return null;
        }
        return (int) $timestamp;
    }

    protected function storeTimestamp(string $timestampName): void
    {
        $this->getTimestampSettingsManager()->storeTimestamp(
            $timestampName,
            (string) time()
        );
    }

    /**
     * @return mixed[]|null
     */
    protected function getJSONData(string $optionName): ?array
    {
        $jsonData = $this->getJSONDataOptionSettingsManager()->getJSONData($optionName);
        if ($jsonData === null) {
            return null;
        }
        return $jsonData;
    }

    /**
     * @param mixed[] $jsonData
     */
    protected function storeJSONData(string $optionName, array $jsonData): void
    {
        $this->getJSONDataOptionSettingsManager()->storeJSONData(
            $optionName,
            $jsonData
        );
    }

    protected function namespaceOption(string $option): string
    {
        return $this->getOptionNamespacer()->namespaceOption($option);
    }
}
