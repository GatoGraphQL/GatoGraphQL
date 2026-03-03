<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceVersion;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialPluginUpdatedPluginData;
use GatoGraphQL\GatoGraphQL\MarketplaceProviders\AbstractMarketplaceProviderCommercialPluginUpdaterService;
use stdClass;
use WP_Error;

/**
 * Based on code from FluentCart's `PluginUpdater` class
 *
 * @see wp-content/plugins/fluent-cart-pro/app/Services/PluginManager/PluginUpdater.php
 *
 * Response by FluentCart API:
 *
 * ```json
 * {
 *     "new_version": "16.1.0",
 *     "stable_version": "16.1.0",
 *     "name": "Gato AI Translations for Polylang",
 *     "slug": "gato-ai-translations-for-polylang",
 *     "url": "https:\/\/gatoplugins.com\/changelog\/ai-translations-for-polylang",
 *     "last_updated": "2026-02-25 06:57:17",
 *     "homepage": "https:\/\/store.gatoplugins.com\/item\/gato-ai-translations-for-polylang\/",
 *     "package": "https:\/\/store.gatoplugins.com\/?fluent-cart=download_license_package&fct_package=...",
 *     "download_link": "https:\/\/store.gatoplugins.com\/?fluent-cart=download_license_package&fct_package=...",
 *     "sections": {
 *         "description": "Automatically translate your content",
 *         "changelog": "<div style=\"color: #e4e4e4;background-color: #181818;font-family: Menlo, Monaco, 'Courier New', monospace;font-weight: normal;font-size: 12px;line-height: 18px;white-space: pre\">\n<div><span style=\"color: #aaa0fa\">##<\/span> <span style=\"color: #aaa0fa\">Added<\/span><\/div>\n<br \/>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Translate user descriptions (<\/span><span style=\"color: #e394dc\">#2280<\/span><span style=\"color: #e4e4e4\">)<\/span><\/div>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Translate user descriptions via WP-CLI (<\/span><span style=\"color: #e394dc\">#2281<\/span><span style=\"color: #e4e4e4\">)<\/span><\/div>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Support Claude Opus 4.6 (<\/span><span style=\"color: #e394dc\">#2297<\/span><span style=\"color: #e4e4e4\">)<\/span><\/div>\n<br \/>\n<div><span style=\"color: #aaa0fa\">##<\/span> <span style=\"color: #aaa0fa\">Improvements<\/span><\/div>\n<br \/>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Allow printing logs in reverse order (<\/span><span style=\"color: #e394dc\">#2279<\/span><span style=\"color: #e4e4e4\">)<\/span><\/div>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Pass <\/span><span style=\"color: #e394dc\">`<\/span><span style=\"color: #e4e4e4\">--default-provider<\/span><span style=\"color: #e394dc\">`<\/span><span style=\"color: #e4e4e4\"> param to WP-CLI for menus (<\/span><span style=\"color: #e394dc\">#2282<\/span><span style=\"color: #e4e4e4\">)<\/span><\/div>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Validate the PHP server has 1GB of memory available (<\/span><span style=\"color: #e394dc\">#2283<\/span><span style=\"color: #e4e4e4\">)<\/span><\/div>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Link to documentation URL to explain how to increase memory for plugin (<\/span><span style=\"color: #e394dc\">#2284<\/span><span style=\"color: #e4e4e4\">)<\/span><\/div>\n<br \/>\n<div><span style=\"color: #aaa0fa\">##<\/span> <span style=\"color: #aaa0fa\">Fixed<\/span><\/div>\n<br \/>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Do not show input for custom posts on the \"Gato Translate (Custom)\" form for taxonomies (#45b09e9f)<\/span><\/div>\n<div><span style=\"color: #d6d6dd\">-<\/span><span style=\"color: #e4e4e4\"> Execute menu translation when automatic translation for CPTs is disabled (#271a08c5)<\/span><\/div>\n<br \/><br \/><\/div>\n<p>\u00a0<\/p>"
 *     },
 *     "banners": {
 *         "low": "https:\/\/gatoplugins.com\/images\/thumbnails\/plugins-logo\/ai-translations-for-polylang-1544x500.jpg",
 *         "high": "https:\/\/gatoplugins.com\/images\/thumbnails\/plugins-logo\/ai-translations-for-polylang-1544x500.jpg"
 *     },
 *     "icons": {
 *         "2x": "https:\/\/gatoplugins.com\/assets\/Gato-logo-suki-256x256.png",
 *         "1x": "https:\/\/gatoplugins.com\/assets\/Gato-logo-suki-256x256.png"
 *     },
 *     "license_status": "valid",
 *     "trunk": "https:\/\/store.gatoplugins.com\/?fluent-cart=download_license_package&fct_package=...",
 *     "success": true
 * }
 * ```
 */
class FluentCartCommercialPluginUpdaterService extends AbstractMarketplaceProviderCommercialPluginUpdaterService
{
    use FluentCartMarketplaceProviderServiceTrait;

    /**
     * Fetch the update info from the remote server running the FluentCart plugin.
     */
    protected function getRemotePluginData(CommercialPluginUpdatedPluginData $pluginData): array|WP_Error
    {
        $url = 'https://store.gatoplugins.com';
        $fullUrl = add_query_arg([
            'fluent-cart' => 'get_license_version',
        ], $url);

        $payload = [
            'item_id'          => $pluginData->marketplaceProductIDs[MarketplaceVersion::V2_FLUENTCART] ?? null,
            'current_version'  => $pluginData->pluginVersion,
            'site_url'         => home_url(),
            'platform_version' => get_bloginfo('version'),
            'server_version'   => PHP_VERSION,
            'license_key'      => $pluginData->licenseKey,
        ];

        return wp_remote_post($fullUrl, [
            'timeout'   => 15,
            'body'      => $payload
        ]);
    }

    protected function maybeAdaptPayload(string $payload): string
    {
        $decodedPayload = json_decode($payload);
        if ($decodedPayload instanceof stdClass && isset($decodedPayload->new_version)) {
            $adaptedResponse = new stdClass();
            $adaptedResponse->success = true;
            $adaptedResponse->error = '';
            $adaptedResponse->error_code = '';
            $adaptedResponse->update = $decodedPayload;
            $adaptedResponse->update->version = $decodedPayload->new_version ?? '';
            $decodedPayload = $adaptedResponse;
            $payload = json_encode($decodedPayload);
            if ($payload === false) {
                return '';
            }
        }
        return $payload;
    }
}
