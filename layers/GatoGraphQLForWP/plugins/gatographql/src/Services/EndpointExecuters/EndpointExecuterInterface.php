<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use PoP\Root\Services\ActivableServiceInterface;

interface EndpointExecuterInterface extends ActivableServiceInterface
{
    public function executeEndpoint(): void;
    public function isEndpointBeingRequested(): bool;
}
