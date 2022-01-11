<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointResolverInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

abstract class AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractEndpointExecuterAppStateProvider
{
    use EndpointResolverAppStateProviderTrait;

    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;
    private ?GraphQLQueryConvertorInterface $graphQLQueryConvertor = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    final public function setGraphQLQueryConvertor(GraphQLQueryConvertorInterface $graphQLQueryConvertor): void
    {
        $this->graphQLQueryConvertor = $graphQLQueryConvertor;
    }
    final protected function getGraphQLQueryConvertor(): GraphQLQueryConvertorInterface
    {
        return $this->graphQLQueryConvertor ??= $this->instanceManager->getInstance(GraphQLQueryConvertorInterface::class);
    }

    protected function getEndpointExecuter(): EndpointExecuterInterface
    {
        return $this->getGraphQLQueryResolutionEndpointExecuter();
    }

    protected function getGraphQLEndpointResolver(): GraphQLEndpointResolverInterface
    {
        return $this->getGraphQLQueryResolutionEndpointExecuter();
    }

    abstract protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface;

    public function initialize(array &$state): void
    {
        $this->initializeState($state);
    }

    public function consolidate(array &$state): void
    {
        $this->consolidateState($state);
    }
}
