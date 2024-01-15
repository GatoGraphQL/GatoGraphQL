<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

interface LicenseValidationServiceInterface
{
    /**
     * Activate the Gato GraphQL Extensions against the
     * marketplace provider's API.
     *
     * @param array<string,string> $previousLicenseKeys Key: Extension Slug, Value: License Key
     * @param array<string,string> $submittedLicenseKeys Key: Extension Slug, Value: License Key
     */
    public function activateDeactivateValidateGatoGraphQLCommercialExtensions(
        array $previousLicenseKeys,
        array $submittedLicenseKeys,
    ): void;
}
