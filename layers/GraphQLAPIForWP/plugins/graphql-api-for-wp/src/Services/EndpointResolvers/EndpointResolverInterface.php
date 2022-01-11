<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointResolverInterface;

interface EndpointResolverInterface extends GraphQLEndpointResolverInterface
{
    /**
     * Indicate if it is executing a GraphQL query
     * against the endpoint
     */
    public function isGraphQLQueryExecution(): bool;
}
