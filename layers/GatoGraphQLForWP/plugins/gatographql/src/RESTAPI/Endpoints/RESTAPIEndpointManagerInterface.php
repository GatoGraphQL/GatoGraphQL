<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\RESTAPI\Endpoints;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;

interface RESTAPIEndpointManagerInterface extends AutomaticallyInstantiatedServiceInterface
{
    public function registerRoutes(): void;
}
