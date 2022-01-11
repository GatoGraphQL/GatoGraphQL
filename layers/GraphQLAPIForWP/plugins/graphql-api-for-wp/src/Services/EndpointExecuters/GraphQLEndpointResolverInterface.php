<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use WP_Post;

interface GraphQLEndpointResolverInterface extends EndpointExecuterInterface
{
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array;
    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool;
}
