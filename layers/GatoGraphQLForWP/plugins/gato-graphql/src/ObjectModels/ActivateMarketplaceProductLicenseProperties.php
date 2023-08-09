<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

class ActivateMarketplaceProductLicenseProperties extends AbstractMarketplaceProductLicenseProperties
{
    /**
     * @param array<string,mixed> $apiResponsePayload
     */
    public function __construct(
        array $apiResponsePayload,
        ?string $status,
        ?string $errorMessage,
        ?string $successMessage,
        public readonly ?string $instanceID,
    ) {
        parent::__construct(
            $apiResponsePayload,
            $status,
            $errorMessage,
            $successMessage,
        );
    }
}
