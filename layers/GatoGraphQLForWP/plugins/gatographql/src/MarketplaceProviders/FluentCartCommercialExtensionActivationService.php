<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\ObjectModels\ActiveLicenseCommercialExtensionData;

use function wp_remote_retrieve_response_code;

/**
 * Based on code from FluentCart's `FluentLicensing` class.
 *
 * @see wp-content/plugins/fluent-cart-pro/app/Services/PluginManager/FluentLicensing.php
 */
class FluentCartCommercialExtensionActivationService extends AbstractMarketplaceProviderCommercialExtensionActivationService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    use FluentCartMarketplaceProviderServiceTrait;

    /**
     * FluentCart identifies instances by site URL,
     * so compare directly against home_url().
     */
    public function isInstanceNameValid(string $instanceName): bool
    {
        return home_url() === $instanceName;
    }

    /**
     * @throws HTTPRequestNotSuccessfulException
     * @throws \GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException
     */
    public function activateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $endpoint = $this->getFluentCartAPIEndpoint('activate_license');
        return $this->handleLicenseOperation($endpoint, $licenseKey, null, [
            'body' => array_merge(
                $this->getFluentCartDefaultPayload($extensionData),
                [
                    'license_key'      => $licenseKey,
                    'platform_version' => get_bloginfo('version'),
                    'server_version'   => PHP_VERSION,
                ],
            ),
        ]);
    }

    /**
     * @throws HTTPRequestNotSuccessfulException
     * @throws \GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException
     */
    public function deactivateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
        string $instanceID,
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $endpoint = $this->getFluentCartAPIEndpoint('deactivate_license');
        return $this->handleLicenseOperation($endpoint, $licenseKey, $instanceID, [
            'body' => array_merge(
                $this->getFluentCartDefaultPayload($extensionData),
                [
                    'license_key' => $licenseKey,
                ],
            ),
        ]);
    }

    /**
     * Maps to FluentCart's `check_license` action.
     *
     * @throws HTTPRequestNotSuccessfulException
     * @throws \GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException
     */
    public function validateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
        string $instanceID,
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $endpoint = $this->getFluentCartAPIEndpoint('check_license');
        return $this->handleLicenseOperation($endpoint, $licenseKey, $instanceID, [
            'body' => array_merge(
                $this->getFluentCartDefaultPayload($extensionData),
                [
                    'license_key'     => $licenseKey,
                    'activation_hash' => $instanceID,
                ],
            ),
        ]);
    }

    protected function getFluentCartAPIEndpoint(string $action): string
    {
        return add_query_arg(
            ['fluent-cart' => $action],
            $this->getFluentCartAPIBaseURL(),
        );
    }

    protected function getFluentCartAPIBaseURL(): string
    {
        return 'https://store.gatoplugins.com';
    }

    /**
     * @return array<string,string|int|null>
     */
    protected function getFluentCartDefaultPayload(
        ?ActiveLicenseCommercialExtensionData $extensionData,
    ): array {
        $marketplaceProductID = $extensionData?->marketplaceProductIDs[$this->getMarketplaceVersion()] ?? null;
        return [
            'item_id'         => $marketplaceProductID,
            'current_version' => $extensionData?->version ?? '',
            'site_url'        => home_url(),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getLicenseOperationRequestArgs(): array
    {
        return [
            'timeout' => 15,
        ];
    }

    /**
     * FluentCart uses 'valid' for active licenses.
     */
    protected function convertStatus(string $status): string
    {
        return match ($status) {
            'valid' => LicenseStatus::ACTIVE,
            'expired' => LicenseStatus::EXPIRED,
            'inactive' => LicenseStatus::INACTIVE,
            'disabled' => LicenseStatus::DISABLED,
            'unregistered' => LicenseStatus::UNREGISTERED,
            default => LicenseStatus::OTHER,
        };
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getLicenseStatusFromResponseBody(array $body): ?string
    {
        /** @var string|null */
        $status = $body['status'] ?? null;
        return $status;
    }

    /**
     * FluentCart returns errors via `error_type` + `message`.
     *
     * @param array<string,mixed> $body
     * @param array<string,mixed> $response
     */
    protected function getErrorFromResponseBody(array $body, array $response): ?string
    {
        $responseCode = wp_remote_retrieve_response_code($response);
        if ($responseCode === 200) {
            return null;
        }

        if (empty($body['error_type'])) {
            return null;
        }

        /** @var string */
        $message = $body['message'] ?? $this->__('Unknown error', 'gatographql');
        return $message;
    }

    /**
     * FluentCart does not have a test mode concept.
     *
     * @param array<string,mixed> $body
     */
    protected function getIsTestModeFromResponseBody(array $body): bool
    {
        return false;
    }

    /**
     * FluentCart uses `activation_hash` as the instance identifier.
     *
     * @param array<string,mixed> $body
     */
    protected function getInstanceIDFromResponseBody(array $body): ?string
    {
        /** @var string|null */
        $activationHash = $body['activation_hash'] ?? null;
        return $activationHash;
    }

    /**
     * FluentCart identifies instances by site URL.
     *
     * @param array<string,mixed> $body
     */
    protected function getInstanceNameFromResponseBody(array $body): ?string
    {
        return home_url();
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getActivationUsageFromResponseBody(array $body): int
    {
        return (int) ($body['activations_count'] ?? 0);
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getActivationLimitFromResponseBody(array $body): int
    {
        return (int) ($body['activation_limit'] ?? 0);
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getProductNameFromResponseBody(array $body): string
    {
        return (string) ($body['product_title'] ?? '');
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getCustomerNameFromResponseBody(array $body): string
    {
        return (string) ($body['customer_name'] ?? '');
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getCustomerEmailFromResponseBody(array $body): string
    {
        return (string) ($body['customer_email'] ?? '');
    }
}
