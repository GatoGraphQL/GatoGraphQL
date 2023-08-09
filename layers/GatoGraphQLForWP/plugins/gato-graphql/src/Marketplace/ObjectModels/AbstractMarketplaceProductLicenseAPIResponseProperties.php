<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

abstract class AbstractMarketplaceProductLicenseAPIResponseProperties implements MarketplaceProductLicenseAPIResponsePropertiesInterface
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

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'apiResponsePayload' => $this->apiResponsePayload,
            'status' => $this->status,
            'errorMessage' => $this->errorMessage,
            'successMessage' => $this->successMessage,
        ];
    }
}
