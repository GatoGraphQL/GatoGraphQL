<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use PoP\ComponentModel\Misc\GeneralUtils;
use WP_Error;

use function home_url;
use function wp_remote_post;
use function wp_remote_retrieve_response_code;
use function wp_remote_retrieve_response_message;

class LemonSqueezyCommercialExtensionActivationService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
     *
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function activateLicense(string $licenseKey): array
    {
        $instanceName = $this->getInstanceName();
        $endpoint = sprintf(
            '%s/v1/licenses/activate?license_key=%s&instance_name=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceName
        );

        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        return $this->processResponse($response);
    }

    /**
     * @param array<string,mixed>|WP_Error $response
     */
    protected function processResponse(array|WP_Error $response): array
    {
        if ($response instanceof WP_Error) {
            $errorMessage = $response->get_error_message();
            // @todo Process error message
            return [];
        }

        $body = json_decode($response['body'], true);

        if (wp_remote_retrieve_response_code($response) !== 200) {
            $errorMessage = $body['error'] ?? wp_remote_retrieve_response_message($response);
            // @todo Process error message
            return [];
        }

        return $body;
    }

    protected function getLemonSqueezyAPIBaseURL(): string
    {
        return 'https://api.lemonsqueezy.com';
    }

    /**
     * @return array<string,string>
     */
    protected function getLemonSqueezyAPIBaseHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    /**
     * Use the site's domain as the instance name in Lemon Squeezy
     */
    protected function getInstanceName(): string
    {
        return GeneralUtils::getHost(home_url());
    }

    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-deactivate
     *
     * @param array<string,mixed> Payload stored in the DB from when calling the activation endpoint
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function deactivateLicense(
        string $licenseKey,
        array $activatedCommercialExtensionLicensePayload
    ): array {
        /**
         * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
         */
        $instanceID = $activatedCommercialExtensionLicensePayload['instance']['id'] ?? '';
        $endpoint = sprintf(
            '%s/v1/licenses/deactivate?license_key=%s&instance_id=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceID
        );

        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        return $this->processResponse($response);
    }
}
