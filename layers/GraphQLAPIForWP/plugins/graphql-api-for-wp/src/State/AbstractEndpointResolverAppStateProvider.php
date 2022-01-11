<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointResolverInterface;
use GraphQLAPI\GraphQLAPI\State\AbstractEndpointExecuterAppStateProvider;

abstract class AbstractEndpointResolverAppStateProvider extends AbstractEndpointExecuterAppStateProvider
{
    protected function getGraphQLEndpointResolver(): GraphQLEndpointResolverInterface
    {
        return $this->getEndpointExecuter();
    }
}
