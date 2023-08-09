<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\ActivateLicenseAPIResponseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\DeactivateLicenseAPIResponseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\ValidateLicenseAPIResponseProperties;
use RuntimeException;
use WP_Error;

use function wp_remote_post;

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
                null,
            );
        }

        $body = json_decode($response['body'], true);

        /**
         * Extract properties from the response.
         *
         * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
         *
         * @var string|null
         */
        $error = $body['license_key']['error'];
        if ($error !== null) {
            return new ActivateLicenseAPIResponseProperties(
                $body,
                $body['license_key']['status'] ?? null,
                $error,
                null,
                $body['instance']['id'] ?? null,
            );
        }

        /** @var string */
        $status = $body['license_key']['status'];
        $status = $this->convertStatus($status);
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
            $instanceID,
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
     * Convert the status: from the value used by LemonSqueezy,
     * to the constants used by Gato GraphQL
     *
     * @see https://docs.lemonsqueezy.com/guides/tutorials/license-keys#license-key-statuses
     */
    protected function convertStatus(string $status): string
    {
        return match($status) {
            'inactive' => LicenseStatus::INACTIVE,
            'active' => LicenseStatus::ACTIVE,
            'expired' => LicenseStatus::EXPIRED,
            'disabled' => LicenseStatus::DISABLED,
            default => new RuntimeException(
                sprintf(
                    \__('Unsupported license status \'%s\'', 'gato-graphql'),
                    $status
                )
            ),
        };
    }

    public function deactivateLicense(
        string $licenseKey,
        string $instanceID
    ): DeactivateLicenseAPIResponseProperties {        
        $endpoint = $this->getDeactivateLicenseEndpoint($licenseKey, $instanceID);
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        if ($response instanceof WP_Error) {
            return new DeactivateLicenseAPIResponseProperties(
                null,
                null,
                $response->get_error_message(),
                null,
            );
        }

        $body = json_decode($response['body'], true);

        /**
         * Extract properties from the response.
         *
         * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-deactivate
         *
         * @var string|null
         */
        $error = $body['license_key']['error'];
        if ($error !== null) {
            return new DeactivateLicenseAPIResponseProperties(
                $body,
                $body['license_key']['status'] ?? null,
                $error,
                null,
            );
        }

        /** @var string */
        $status = $body['license_key']['status'];
        $status = $this->convertStatus($status);
        /** @var string */
        $activationUsage = $body['license_key']['activation_usage'];
        /** @var string */
        $activationLimit = $body['license_key']['activation_limit'];
        return new DeactivateLicenseAPIResponseProperties(
            $body,
            $status,
            null,
            sprintf(
                \__('License for this instance has been deactivated. You now have %s/%s instances activated.', 'gato-graphql'),
                $activationUsage,
                $activationLimit,
            ),
        );
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

    public function validateLicense(
        string $licenseKey,
        string $instanceID
    ): ValidateLicenseAPIResponseProperties {
        $endpoint = $this->getValidateLicenseEndpoint($licenseKey, $instanceID);
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        if ($response instanceof WP_Error) {
            return new ValidateLicenseAPIResponseProperties(
                null,
                null,
                $response->get_error_message(),
                null,
            );
        }

        $body = json_decode($response['body'], true);

        /**
         * Extract properties from the response.
         *
         * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-validate
         *
         * @var string|null
         */
        $error = $body['license_key']['error'];
        if ($error !== null) {
            return new ValidateLicenseAPIResponseProperties(
                $body,
                $body['license_key']['status'] ?? null,
                $error,
                null,
            );
        }

        /** @var string */
        $status = $body['license_key']['status'];
        $status = $this->convertStatus($status);
        /** @var string */
        $instanceID = $body['instance']['id'];
        /** @var string */
        $activationUsage = $body['license_key']['activation_usage'];
        /** @var string */
        $activationLimit = $body['license_key']['activation_limit'];
        return new ValidateLicenseAPIResponseProperties(
            $body,
            $status,
            null,
            sprintf(
                \__('License is active. You have %s/%s instances activated.', 'gato-graphql'),
                $activationUsage,
                $activationLimit,
            ),
        );
    }

    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-validate
     */
    protected function getValidateLicenseEndpoint(
        string $licenseKey,
        string $instanceID
    ): string {
        return sprintf(
            '%s/v1/licenses/validate?license_key=%s&instance_id=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceID
        );
    }
}
