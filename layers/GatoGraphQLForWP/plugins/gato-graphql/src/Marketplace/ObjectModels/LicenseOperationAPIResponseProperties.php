<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels;

use PoP\Root\Exception\ShouldNotHappenException;

class LicenseOperationAPIResponseProperties
{
    /**
     * @param array<string,mixed>|null $apiResponsePayload
     */
    public function __construct(
        public readonly ?array $apiResponsePayload,
        public readonly ?string $status,
        public readonly ?string $errorMessage,
        public readonly ?string $successMessage,
        public readonly ?string $instanceID,
    ) {
        if ($errorMessage === null) {
            if ($successMessage === null) {
                throw new ShouldNotHappenException(
                    \__('The operation must either have an error, or be successful', 'gato-graphql')
                );
            }
            if ($apiResponsePayload === null) {
                throw new ShouldNotHappenException(
                    \__('Must provide the API response payload for a successful operation', 'gato-graphql')
                );
            }
            if ($status === null) {
                throw new ShouldNotHappenException(
                    \__('Must provide the status for a successful operation', 'gato-graphql')
                );
            }
            if ($instanceID === null) {
                throw new ShouldNotHappenException(
                    \__('Must provide the instance ID for a successful operation', 'gato-graphql')
                );
            }
        } else {
            if ($successMessage !== null) {
                throw new ShouldNotHappenException(
                    \__('The operation cannot have error and success at the same time', 'gato-graphql')
                );
            }
        }
    }

    public function isSuccessful(): bool
    {
        return $this->errorMessage === null;
    }
}
