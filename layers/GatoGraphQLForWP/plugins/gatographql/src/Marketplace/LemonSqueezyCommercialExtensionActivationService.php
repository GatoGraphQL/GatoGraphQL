<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginVersionHelpers;
use PoP\Root\Services\BasicServiceTrait;
use WP_Error;

use function wp_remote_post;

class LemonSqueezyCommercialExtensionActivationService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    use BasicServiceTrait;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function activateLicense(string $licenseKey, string $instanceName): CommercialExtensionActivatedLicenseObjectProperties
    {
        $endpoint = $this->getActivateLicenseEndpoint($licenseKey, $instanceName);
        return $this->handleLicenseOperation($endpoint, $licenseKey, null);
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
     * Process the API response for the /activate, /deactivate and /validate endpoints.
     *
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-deactivate
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-validate
     *
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    protected function handleLicenseOperation(
        string $endpoint,
        string $licenseKey,
        ?string $instanceID
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        if ($response instanceof WP_Error) {
            throw new HTTPRequestNotSuccessfulException($response->get_error_message());
        }

        /**
         * Skip this check, because LemonSqueezy might return a 400 when
         * the activation is not successful, but we want to capture the
         * error message below.
         */
        // $responseCode = wp_remote_retrieve_response_code($response);
        // if ($responseCode !== 200) {
        //     $errorMessage = wp_remote_retrieve_response_message($response);
        //     throw new HTTPRequestNotSuccessfulException($errorMessage);
        // }

        $body = json_decode($response['body'], true);

        /**
         * Check the "status" first, and only then the "error",
         * because "expired" is a valid state for Gato GraphQL,
         * but this also produces an error in LemonSqueezy
         *
         * @var string|null
         */
        $status = $body['license_key']['status'] ?? null;
        /** @var string|null */
        $error = $body['error'];
        if ($status === null) {
            /** @var string $error */
            throw new LicenseOperationNotSuccessfulException($error);
        }

        $status = $this->convertStatus($status);

        /**
         * Deactivating the license may bring status "inactive"
         * and no error, then don't throw error. When status is
         * "disabled" it will also bring an error, then throw it.
         */
        if (
            $error !== null
            && !in_array($status, [
            LicenseStatus::ACTIVE,
            LicenseStatus::EXPIRED,
            ])
        ) {
            /** @var string $error */
            throw new LicenseOperationNotSuccessfulException($error);
        }

        /**
         * Throw an "error" from the response, unless:
         *
         * - The license is "expired", and
         * - The license had been previously activated (i.e. there's an instance ID)
         *
         * The last item is important as ["instance"]["id"] is not sent in case of error
         */
        if (
            $error !== null
            && (
                $status !== LicenseStatus::EXPIRED
                || $instanceID === null
            )
        ) {
            throw new LicenseOperationNotSuccessfulException($error);
        }

        /**
         * By now, either $error is null, or it's for the "expired" status.
         * In either case, all properties below will be set in the response,
         * so no need to do ?? null.
         *
         * If the license is on the Gato Shop on Test mode,
         * then only enable it for the extension in DEV.
         *
         * @var bool
         */
        $isTestMode = $body['license_key']['test_mode'] ?? false;
        /**
         * Notice that we validate "-dev" against the main Gato GraphQL
         * plugin and not against the extension, but it still works
         * because these are the same.
         *
         * @see method `assertIsSameEnvironmentAsMainPlugin` in `ExtensionManager`
         */
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();
        $isExtensionOnDevelopmentMode = PluginVersionHelpers::isDevelopmentVersion($mainPluginVersion);
        if ($isTestMode && !$isExtensionOnDevelopmentMode) {
            throw new LicenseOperationNotSuccessfulException(
                $this->__('The license is for test mode, but the extension is not on development mode', 'gatographql'),
            );
        } elseif (!$isTestMode && $isExtensionOnDevelopmentMode) {
            throw new LicenseOperationNotSuccessfulException(
                $this->__('The license is not for test mode, but the extension is on development mode', 'gatographql'),
            );
        }

        /**
         * For the /activate endpoint, retrieve the instance ID from the response.         *
         * For the /deactivate endpoint, there will be no "instance" entry.
         *
         * @var string|null
         */
        $instanceID = $body['instance']['id'] ?? null;
        /** @var string|null */
        $instanceName = $body['instance']['name'] ?? null;

        /**
         * These should always be provided, but just in case there's
         * no "license_key" in the response, default it to `0`.
         */
        $activationUsage = (int) ($body['license_key']['activation_usage'] ?? 0);
        $activationLimit = (int) ($body['license_key']['activation_limit'] ?? 0);

        /** @var string */
        $productName = $body['meta']['product_name'];
        /** @var string */
        $customerName = $body['meta']['customer_name'];
        /** @var string */
        $customerEmail = $body['meta']['customer_email'];

        return new CommercialExtensionActivatedLicenseObjectProperties(
            $licenseKey,
            $body,
            $status,
            $instanceID,
            $instanceName,
            $activationUsage,
            $activationLimit,
            $productName,
            $customerName,
            $customerEmail,
        );
    }

    /**
     * Convert the status: from the value used by LemonSqueezy,
     * to the constants used by Gato GraphQL
     *
     * @see https://docs.lemonsqueezy.com/guides/tutorials/license-keys#license-key-statuses
     */
    protected function convertStatus(string $status): string
    {
        return match ($status) {
            'active' => LicenseStatus::ACTIVE,
            'expired' => LicenseStatus::EXPIRED,
            'inactive' => LicenseStatus::INACTIVE,
            'disabled' => LicenseStatus::DISABLED,
            default => LicenseStatus::OTHER,
        };
    }

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function deactivateLicense(
        string $licenseKey,
        string $instanceID
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $endpoint = $this->getDeactivateLicenseEndpoint($licenseKey, $instanceID);
        return $this->handleLicenseOperation($endpoint, $licenseKey, $instanceID);
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
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function validateLicense(
        string $licenseKey,
        string $instanceID
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $endpoint = $this->getValidateLicenseEndpoint($licenseKey, $instanceID);
        return $this->handleLicenseOperation($endpoint, $licenseKey, $instanceID);
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
