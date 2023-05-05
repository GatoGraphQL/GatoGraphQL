<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use PoP\Root\Services\ServiceInterface;

interface EndpointExecuterInterface extends ServiceInterface
{
    public function executeEndpoint(): void;
    public function isEndpointBeingRequested(): bool;
}
