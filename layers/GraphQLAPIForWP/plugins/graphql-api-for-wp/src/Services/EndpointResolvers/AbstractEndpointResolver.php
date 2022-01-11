<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\AbstractEndpointExecuter;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;

abstract class AbstractEndpointResolver extends AbstractEndpointExecuter implements GraphQLEndpointExecuterInterface
{
    private ?EndpointHelpers $endpointHelpers = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
}
