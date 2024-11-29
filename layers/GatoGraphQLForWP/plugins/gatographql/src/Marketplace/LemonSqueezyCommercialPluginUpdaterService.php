<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialPluginUpdatedPluginData;

use function get_transient;
use function is_wp_error;
use function set_transient;
use function wp_remote_get;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;

/**
 * Copied code from `Make-Lemonade/lemonsqueezy-wp-updater-example`
 *
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example/blob/7c0c71876309939b07d96e270f4db8568f3148cb/includes/class-plugin-updater.php
 */
class LemonSqueezyCommercialPluginUpdaterService extends AbstractMarketplaceProviderCommercialPluginUpdaterService
{
    protected function providePluginUpdatesAPIURL(string $pluginUpdatesServerURL): string
    {
        return sprintf(
            '/wp-json/lsq/v1',
            $pluginUpdatesServerURL
        );
    }

	/**
	 * Fetch the update info from the remote server running the Lemon Squeezy plugin.
	 */
	protected function request(CommercialPluginUpdatedPluginData $pluginData): object|bool
    {
		$remote = get_transient($pluginData->cacheKey);
		if ($remote !== false && $this->cacheAllowed) {
			if ($remote === 'error') {
				return false;
			}

			return json_decode($remote);
		}

		$remote = wp_remote_get(
			$this->apiURL . "/update?license_key={$pluginData->licenseKey}",
			[
				'timeout' => 10,
            ]
		);

		if (is_wp_error($remote)
			|| wp_remote_retrieve_response_code($remote) !== 200
			|| empty(wp_remote_retrieve_body($remote))
		) {
			set_transient($pluginData->cacheKey, 'error', MINUTE_IN_SECONDS * 10);
			return false;
		}

		$payload = wp_remote_retrieve_body($remote);
		set_transient($pluginData->cacheKey, $payload, DAY_IN_SECONDS);
		return json_decode($payload);
	}
}
