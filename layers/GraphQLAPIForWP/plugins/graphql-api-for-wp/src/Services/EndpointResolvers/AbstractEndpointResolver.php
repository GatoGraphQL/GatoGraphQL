<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\BasicService\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractEndpointResolver extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    private ?EndpointHelpers $endpointHelpers = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    /**
     * Initialize the resolver
     */
    public function initialize(): void
    {
        // Do nothing
    }
}
