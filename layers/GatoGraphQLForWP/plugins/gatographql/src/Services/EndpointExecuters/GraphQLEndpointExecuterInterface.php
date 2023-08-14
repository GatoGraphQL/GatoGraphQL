<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\ObjectModels\NullableGraphQLQueryVariablesEntry;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\EndpointExecuterInterface;
use WP_Post;

interface GraphQLEndpointExecuterInterface extends EndpointExecuterInterface
{
    /**
     * Provide the query to execute and its variables
     */
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): NullableGraphQLQueryVariablesEntry;
    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool;
}
