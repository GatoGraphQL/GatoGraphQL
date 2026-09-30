<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

use GatoGraphQL\GatoGraphQL\Marketplace\Enums\LicenseStatus;

class CommercialExtensionActivatedLicenseObjectProperties
{
    /**
     * @param array<string,mixed> $apiResponsePayload
     * @param string|null $instanceID `null` for /deactivate, with value otherwise
     * @param string|null $instanceName `null` for /deactivate, with value otherwise
     */
    public function __construct(
        public readonly string $licenseKey,
        public readonly array $apiResponsePayload,
        public readonly LicenseStatus $status,
        public readonly ?string $instanceID,
        public readonly ?string $instanceName,
        public readonly int $activationUsage,
        public readonly int $activationLimit,
        public readonly string $productName,
        public readonly string|int|null $productID,
        public readonly ?string $customerName,
        public readonly ?string $customerEmail,
    ) {
    }
}
