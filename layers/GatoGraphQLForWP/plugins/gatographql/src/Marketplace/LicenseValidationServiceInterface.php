<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

interface LicenseValidationServiceInterface
{
    /**
     * Activate, deactivate or validate each of the Gato GraphQL
     * Extensions against the marketplace provider's API.
     *
     * @param array<string,string> $previousLicenseKeys Key: Extension Slug, Value: License Key
     * @param array<string,string> $submittedLicenseKeys Key: Extension Slug, Value: License Key
     */
    public function activateDeactivateValidateGatoGraphQLCommercialExtensions(
        array $previousLicenseKeys,
        array $submittedLicenseKeys,
        ?string $formSettingName = null,
    ): void;

    /**
     * Re-validate the Gato GraphQL Extensions against the
     * marketplace provider's API.
     *
     * @param array<string,string> $activeLicenseKeys Key: Extension Slug, Value: License Key
     */
    public function validateGatoGraphQLCommercialExtensions(
        array $activeLicenseKeys,
        ?string $formSettingName = null,
    ): void;
}
