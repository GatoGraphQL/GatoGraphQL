<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\ObjectModels\ActiveLicenseCommercialExtensionData;
use PoP\ComponentModel\Misc\GeneralUtils;

class LemonSqueezyCommercialExtensionActivationService extends AbstractMarketplaceProviderCommercialExtensionActivationService
{
    use LemonSqueezyMarketplaceProviderServiceTrait;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function activateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $instanceName = $this->getInstanceName($extensionData);
        $endpoint = $this->getActivateLicenseEndpoint($licenseKey, $instanceName);
        return $this->handleLicenseOperation($endpoint, $licenseKey, null);
    }

    /**
     * Use the site's domain + extension slug as instance name,
     * to identify on what domain and for which extension the license was activated.
     */
    protected function getInstanceName(?ActiveLicenseCommercialExtensionData $extensionData): string
    {
        $extensionSlug = $extensionData->slug ?? '';
        return sprintf('%s (%s)', $this->getSiteDomain(), $extensionSlug);
    }

    protected function getSiteDomain(): string
    {
        return GeneralUtils::getHost(home_url());
    }

    /**
     * The instance name format is "domain (slug)".
     * Extract the domain and compare with the current site.
     */
    public function isInstanceNameValid(string $instanceName): bool
    {
        return $this->getSiteDomain() === $this->getLicenseDomain($instanceName);
    }

    /**
     * The instance name is composed by:
     *
     * - The site's domain
     * - The extension slug
     *
     * These two are separated by a space. Therefore, the license domain is the site's domain
     */
    protected function getLicenseDomain(string $instanceName): string
    {
        return explode(' ', $instanceName)[0];
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
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function deactivateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
        string $instanceID,
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
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
        string $instanceID,
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
     * @return array<string,mixed>
     */
    protected function getLicenseOperationRequestArgs(): array
    {
        return [
            'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
        ];
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getLicenseStatusFromResponseBody(array $body): ?string
    {
        /** @var string|null */
        $status = $body['license_key']['status'] ?? null;
        return $status;
    }

    /**
     * @param array<string,mixed> $body
     * @param array<string,mixed> $response
     */
    protected function getErrorFromResponseBody(array $body, array $response): ?string
    {
        /** @var string|null */
        $error = $body['error'] ?? null;
        return $error;
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getIsTestModeFromResponseBody(array $body): bool
    {
        /** @var bool */
        $isTestMode = $body['license_key']['test_mode'] ?? false;
        return $isTestMode;
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getInstanceIDFromResponseBody(array $body): ?string
    {
        /** @var string|null */
        $instanceID = $body['instance']['id'] ?? null;
        return $instanceID;
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getInstanceNameFromResponseBody(array $body): ?string
    {
        /** @var string|null */
        $instanceName = $body['instance']['name'] ?? null;
        return $instanceName;
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getActivationUsageFromResponseBody(array $body): int
    {
        return (int) ($body['license_key']['activation_usage'] ?? 0);
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getActivationLimitFromResponseBody(array $body): int
    {
        return (int) ($body['license_key']['activation_limit'] ?? 0);
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getProductNameFromResponseBody(array $body): string
    {
        /** @var string */
        $productName = $body['meta']['product_name'];
        return $productName;
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getProductIDFromResponseBody(array $body): string|int|null
    {
        return null;
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getCustomerNameFromResponseBody(array $body): ?string
    {
        /** @var string */
        $customerName = $body['meta']['customer_name'];
        return $customerName;
    }

    /**
     * @param array<string,mixed> $body
     */
    protected function getCustomerEmailFromResponseBody(array $body): ?string
    {
        /** @var string */
        $customerEmail = $body['meta']['customer_email'];
        return $customerEmail;
    }
}
