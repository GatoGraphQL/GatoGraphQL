<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\EndpointResolverTrait;
use WP_Post;

abstract class AbstractGraphQLQueryResolutionEndpointExecuter extends AbstractEndpointExecuter
{
    use EndpointResolverTrait;

    protected function getView(): string
    {
        return '';
    }

    public function executeEndpoint(): void
    {
        $this->executeGraphQLQuery();
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     */
    protected function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        // If null, we are in the admin (eg: editing a Persisted Query),
        // and there's no need to override params
        return $customPost !== null;
    }
}
