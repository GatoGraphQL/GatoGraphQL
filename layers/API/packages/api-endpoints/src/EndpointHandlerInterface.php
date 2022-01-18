<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpoints;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;

interface EndpointHandlerInterface extends AutomaticallyInstantiatedServiceInterface
{
    /**
     * Provide the endpoint
     */
    public function getEndpoint(): string;

    /**
     * Indicate if the endpoint has been requested
     */
    public function isEndpointRequested(): bool;
}
