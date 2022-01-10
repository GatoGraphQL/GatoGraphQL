<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use PoP\Root\Services\ServiceInterface;
use WP_Post;

interface EndpointExecuterInterface extends ServiceInterface
{
    public function executeEndpoint(): void;
}
