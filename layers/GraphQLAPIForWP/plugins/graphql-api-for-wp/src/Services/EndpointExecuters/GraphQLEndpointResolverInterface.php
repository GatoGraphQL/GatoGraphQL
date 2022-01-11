<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use WP_Post;

interface GraphQLEndpointResolverInterface
{
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array;
    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool;
}
