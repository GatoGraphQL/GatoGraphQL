<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceVersion;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceLicensePrefixes;
use GatoGraphQL\GatoGraphQL\PluginApp;

use function home_url;
use function wp_parse_url;

trait FluentCartMarketplaceProviderServiceTrait
{
    public function getPriority(): int
    {
        return 100;
    }

    public function canProcessLicense(string $licenseKey): bool
    {
        return str_starts_with($licenseKey, MarketplaceLicensePrefixes::V2_FLUENTCART);
    }

    public function getMarketplaceVersion(): string
    {
        return MarketplaceVersion::V2_FLUENTCART;
    }

    protected function getFluentCartAPIBaseURL(): string
    {
        return 'https://store.gatoplugins.com';
    }

    /**
     * Identify the requests that the plugin sends to the Gato Store.
     */
    protected function getFluentCartRequestUserAgent(): string
    {
        return sprintf(
            'GatoPlugins/%s; %s',
            $this->getFluentCartRequestPluginVersion(),
            home_url(),
        );
    }

    protected function getFluentCartRequestPluginVersion(): string
    {
        return PluginApp::getMainPlugin()->getPluginVersion();
    }

    /**
     * Add the user agent to any request sent to the Gato Store,
     * including the package downloads that WordPress itself performs.
     *
     * @param array<string,mixed> $args
     * @return array<string,mixed>
     *
     * @see https://developer.wordpress.org/reference/hooks/http_request_args/
     */
    public function addUserAgentToFluentCartRequest(array $args, string $url): array
    {
        if (wp_parse_url($url, PHP_URL_HOST) !== wp_parse_url($this->getFluentCartAPIBaseURL(), PHP_URL_HOST)) {
            return $args;
        }
        $args['user-agent'] = $this->getFluentCartRequestUserAgent();
        return $args;
    }
}
