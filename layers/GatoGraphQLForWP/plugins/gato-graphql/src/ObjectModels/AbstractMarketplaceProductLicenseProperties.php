<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

abstract class AbstractMarketplaceProductLicenseProperties
{
    /**
     * @param array<string,mixed> $apiResponsePayload
     */
    public function __construct(
        public readonly array $apiResponsePayload,
        public readonly ?string $status,
        public readonly ?string $errorMessage,
        public readonly ?string $successMessage,
    ) {
    }
}
