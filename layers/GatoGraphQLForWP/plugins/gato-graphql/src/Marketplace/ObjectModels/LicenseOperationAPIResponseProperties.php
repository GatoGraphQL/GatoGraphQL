<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

class LicenseOperationAPIResponseProperties
{
    /**
     * @param array<string,mixed> $apiResponsePayload
     */
    public function __construct(
        public readonly array $apiResponsePayload,
        public readonly string $status,
        public readonly string $instanceID,
        public readonly int $activationUsage,
        public readonly int $activationLimit,
    ) {
    }
}
