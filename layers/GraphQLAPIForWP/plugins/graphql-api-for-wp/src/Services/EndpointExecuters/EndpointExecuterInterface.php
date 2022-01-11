<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use PoP\Root\Services\ServiceInterface;

interface EndpointExecuterInterface extends ServiceInterface
{
    public function executeEndpoint(): void;
    public function isClientRequested(): bool;
}
