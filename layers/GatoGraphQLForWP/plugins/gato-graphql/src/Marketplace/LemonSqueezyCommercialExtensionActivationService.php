<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\ActivateLicenseAPIResponseProperties;
use WP_Error;

use function wp_remote_post;
use function wp_remote_retrieve_response_code;
use function wp_remote_retrieve_response_message;

class LemonSqueezyCommercialExtensionActivationService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activateLicense(string $licenseKey, string $instanceName): ActivateLicenseAPIResponseProperties
    {
        $endpoint = $this->getActivateLicenseEndpoint($licenseKey, $instanceName);
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        if ($response instanceof WP_Error) {
            return new ActivateLicenseAPIResponseProperties(
                null,
                null,
                $response->get_error_message(),
                null,
                null
            );
        }

        $body = json_decode($response['body'], true);

        if (wp_remote_retrieve_response_code($response) !== 200) {
            return new ActivateLicenseAPIResponseProperties(
                $body,
                null,
                $body['error'] ?? \__('Unspecified error', 'gato-graphql'),
                null,
                null
            );
        }

        /**
         * Extract properties from the response.
         *
         * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
         *
         * @var string
         */
        $status = $body['license_key']['status'];
        /** @var string */
        $instanceID = $body['instance']['id'];
        /** @var string */
        $activationUsage = $body['license_key']['activation_usage'];
        /** @var string */
        $activationLimit = $body['license_key']['activation_limit'];
        return new ActivateLicenseAPIResponseProperties(
            $body,
            $status,
            null,
            sprintf(
                \__('License is active. You have %s/%s instances activated.', 'gato-graphql'),
                $activationUsage,
                $activationLimit,
            ),
            $instanceID
        );
    }

    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
     */
    protected function getActivateLicenseEndpoint(string $licenseKey, string $instanceName): string
    {
        return sprintf(
            '%s/v1/licenses/activate?license_key=%s&instance_name=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceName
        );
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
        $instanceID = $activatedCommercialExtensionLicensePayload['instance']['id'] ?? null;
        if ($instanceID === null) {
            $errorMessage = '';
            // @todo Process error message
            return [];
        }
        
        $endpoint = $this->getDeactivateLicenseEndpoint($licenseKey, $instanceID);
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        return $this->processResponse($response);
    }

    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-deactivate
     */
    protected function getDeactivateLicenseEndpoint(
        string $licenseKey,
        string $instanceID
    ): string {
        return sprintf(
            '%s/v1/licenses/deactivate?license_key=%s&instance_id=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceID
        );
    }

    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-validate
     *
     * @param array<string,mixed> Payload stored in the DB from when calling the activation endpoint
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function validateLicense(
        string $licenseKey,
        ?array $activatedCommercialExtensionLicensePayload
    ): array {
        if ($activatedCommercialExtensionLicensePayload !== null) {
            /**
             * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
             */
            $instanceID = $activatedCommercialExtensionLicensePayload['instance']['id'] ?? null;
        } else {
            $instanceID = null;
        }
        $endpoint = $this->getValidateLicenseEndpoint($licenseKey, $instanceID);
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        return $this->processResponse($response);
    }

    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-validate
     */
    protected function getValidateLicenseEndpoint(
        string $licenseKey,
        ?string $instanceID
    ): string {
        return sprintf(
            '%s/v1/licenses/validate?license_key=%s&instance_id=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceID ?? ''
        );
    }
}
