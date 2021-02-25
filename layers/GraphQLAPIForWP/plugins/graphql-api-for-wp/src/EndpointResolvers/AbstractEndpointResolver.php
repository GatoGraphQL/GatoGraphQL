<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractEndpointResolver extends AbstractAutomaticallyInstantiatedService
{
    protected EndpointHelpers $endpointHelpers;

    function __construct(EndpointHelpers $endpointHelpers)
    {
        $this->endpointHelpers = $endpointHelpers;
    }

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
