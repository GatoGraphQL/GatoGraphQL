<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginVersionHelpers;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

use function wp_remote_post;

abstract class AbstractMarketplaceProviderCommercialExtensionActivationService extends AbstractBasicService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    /**
     * Process the API response for the /activate, /deactivate and /validate endpoints.
     *
     * @param array<string,mixed> $extraRequestArgs Additional request args merged into the base args (e.g. POST body)
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    protected function handleLicenseOperation(
        string $endpoint,
        string $licenseKey,
        ?string $instanceID,
        array $extraRequestArgs = [],
    ): CommercialExtensionActivatedLicenseObjectProperties {
        $requestArgs = array_merge_recursive(
            $this->getLicenseOperationRequestArgs(),
            $extraRequestArgs,
        );
        $response = wp_remote_post(
            $endpoint,
            $requestArgs,
        );

        if ($response instanceof WP_Error) {
            throw new HTTPRequestNotSuccessfulException($response->get_error_message());
        }

        /** @var array<string,mixed> $body */
        $body = json_decode($response['body'], true);

        /**
         * Check the "status" first, and only then the "error",
         * because "expired" is a valid state for Gato GraphQL,
         * but this may also produce an error in the Marketplace Provider.
         */
        $status = $this->getLicenseStatusFromResponseBody($body);
        $error = $this->getErrorFromResponseBody($body, $response);
        if ($status === null) {
            throw new LicenseOperationNotSuccessfulException($error ?? $this->__('Unknown error', 'gatographql'));
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
            ], true)
        ) {
            throw new LicenseOperationNotSuccessfulException($error);
        }

        /**
         * Throw an "error" from the response, unless:
         *
         * - The license is "expired", and
         * - The license had been previously activated (i.e. there's an instance ID)
         *
         * The last item is important as ["instance"]["id"] is not sent in case of error.
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
         */
        $isTestMode = $this->getIsTestModeFromResponseBody($body);
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();
        $isExtensionOnDevelopmentMode = PluginVersionHelpers::isDevelopmentVersion($mainPluginVersion);
        if ($isTestMode && !$isExtensionOnDevelopmentMode) {
            throw new LicenseOperationNotSuccessfulException(
                $this->__('The license is for test mode, but the extension is not on development mode', 'gatographql'),
            );
        }

        if (
            !PluginStaticModuleConfiguration::canDevModePluginUseProdModeLicense()
            && !$isTestMode
            && $isExtensionOnDevelopmentMode
        ) {
            throw new LicenseOperationNotSuccessfulException(
                $this->__('The license is not for test mode, but the extension is on development mode', 'gatographql'),
            );
        }

        /**
         * For the /activate endpoint, retrieve the instance ID from the response.
         * For the /deactivate endpoint, there will be no "instance" entry.
         */
        $instanceID = $this->getInstanceIDFromResponseBody($body);
        $instanceName = $this->getInstanceNameFromResponseBody($body);

        /**
         * These should always be provided, but just in case there's
         * no "license_key" in the response, default it to `0`.
         */
        $activationUsage = $this->getActivationUsageFromResponseBody($body);
        $activationLimit = $this->getActivationLimitFromResponseBody($body);

        $productName = $this->getProductNameFromResponseBody($body);
        $productID = $this->getProductIDFromResponseBody($body);
        $customerName = $this->getCustomerNameFromResponseBody($body);
        $customerEmail = $this->getCustomerEmailFromResponseBody($body);

        return new CommercialExtensionActivatedLicenseObjectProperties(
            $licenseKey,
            $body,
            $status,
            $instanceID,
            $instanceName,
            $activationUsage,
            $activationLimit,
            $productName,
            $productID,
            $customerName,
            $customerEmail,
        );
    }

    /**
     * Convert the status: from the value used by the Marketplace Provider,
     * to the constants used by Gato GraphQL.
     */
    abstract protected function convertStatus(string $status): string;

    /**
     * @return array<string,mixed>
     */
    abstract protected function getLicenseOperationRequestArgs(): array;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getLicenseStatusFromResponseBody(array $body): ?string;

    /**
     * @param array<string,mixed> $body
     * @param array<string,mixed> $response
     */
    abstract protected function getErrorFromResponseBody(array $body, array $response): ?string;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getIsTestModeFromResponseBody(array $body): bool;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getInstanceIDFromResponseBody(array $body): ?string;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getInstanceNameFromResponseBody(array $body): ?string;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getActivationUsageFromResponseBody(array $body): int;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getActivationLimitFromResponseBody(array $body): int;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getProductNameFromResponseBody(array $body): string;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getProductIDFromResponseBody(array $body): string|int|null;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getCustomerNameFromResponseBody(array $body): ?string;

    /**
     * @param array<string,mixed> $body
     */
    abstract protected function getCustomerEmailFromResponseBody(array $body): ?string;
}
