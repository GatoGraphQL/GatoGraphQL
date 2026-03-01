<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialPluginUpdatedPluginData;
use WP_Error;

use function wp_remote_get;

/**
 * Copied code from `Make-Lemonade/lemonsqueezy-wp-updater-example`
 *
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example/blob/7c0c71876309939b07d96e270f4db8568f3148cb/includes/class-plugin-updater.php
 *
 * Response by LemonSqueezy API:
 *
 * ```json
 * {
 *     "success": true,
 *     "error": "",
 *     "error_code": "",
 *     "update": {
 *         "version": "16.1.0",
 *         "tested": null,
 *         "requires": null,
 *         "author": "Gato Shop",
 *         "author_profile": "https:\/\/shop.gatoplugins.com",
 *         "download_link": "https:\/\/app.lemonsqueezy.com\/download\/{license_key}?expires=1772371546&signature=...",
 *         "trunk": "https:\/\/app.lemonsqueezy.com\/download\/{license_key}?expires=1772371546&signature=...",
 *         "requires_php": null,
 *         "last_updated": null,
 *         "sections": {
 *             "description": "<p>Automatically translate your content.<\/p>",
 *             "changelog": "16.1.0"
 *         }
 *     }
 * }
 * ```
 */
class LemonSqueezyCommercialPluginUpdaterService extends AbstractMarketplaceProviderCommercialPluginUpdaterService
{
    use LemonSqueezyMarketplaceProviderServiceTrait;

    /**
     * Fetch the update info from the remote server running the Lemon Squeezy plugin.
     */
    protected function getRemotePluginData(CommercialPluginUpdatedPluginData $pluginData): array|WP_Error
    {
        $url = "https://updates.gatoplugins.com/wp-json/lsq/v1/update?license_key={$pluginData->licenseKey}";
        return wp_remote_get($url, ['timeout' => 10]);
    }
}
