<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\EndpointExecuterInterface;
use WP_Post;

interface GraphQLEndpointExecuterInterface extends EndpointExecuterInterface
{
    /**
     * Provide the query to execute and its variables
     *
     * @return array{0:?string,1:?array<string,mixed>} Array of 2 elements: [query, variables]
     */
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array;
    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool;
}
