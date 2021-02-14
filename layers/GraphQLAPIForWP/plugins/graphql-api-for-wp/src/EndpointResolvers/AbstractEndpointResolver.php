<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointResolvers;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractEndpointResolver extends AbstractAutomaticallyInstantiatedService
{
    /**
     * Initialize the resolver
     *
     * @return void
     */
    public function initialize(): void
    {
        // Do nothing
    }
}
