<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\EndpointResolverTrait;

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
}
