<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

abstract class AbstractMarketplaceProductLicenseAPIResponseProperties
{
    /**
     * @param array<string,mixed>|null $apiResponsePayload
     */
    public function __construct(
        public readonly ?array $apiResponsePayload,
        public readonly ?string $status,
        public readonly ?string $errorMessage,
        public readonly ?string $successMessage,
    ) {
    }

    public function isSuccessful(): bool
    {
        return $this->errorMessage === null;
    }
}
