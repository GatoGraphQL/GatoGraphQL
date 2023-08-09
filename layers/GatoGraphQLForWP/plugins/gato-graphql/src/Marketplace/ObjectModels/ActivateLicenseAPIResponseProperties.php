<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

use PoP\Root\Exception\ShouldNotHappenException;

class ActivateLicenseAPIResponseProperties extends AbstractMarketplaceProductLicenseAPIResponseProperties
{
    /**
     * @param array<string,mixed>|null $apiResponsePayload
     */
    public function __construct(
        ?array $apiResponsePayload,
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
        
        if ($errorMessage === null) {
            if ($instanceID === null) {
                throw new ShouldNotHappenException(
                    \__('Must provide the instance ID for a successful operation', 'gato-graphql')
                );
            }
        }
    }
}
