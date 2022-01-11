<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointResolverInterface;
use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\State\AbstractAppStateProvider;

abstract class AbstractEndpointResolverAppStateProvider extends AbstractAppStateProvider
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

    abstract protected function getEndpointResolver(): EndpointExecuterInterface;

    protected function getGraphQLEndpointResolver(): GraphQLEndpointResolverInterface
    {
        return $this->getEndpointResolver();
    }

    public function isServiceEnabled(): bool
    {
        return $this->getEndpointResolver()->isEndpointBeingRequested();
    }

    public function initialize(array &$state): void
    {
        $this->initializeState($state);
    }

    public function consolidate(array &$state): void
    {
        $this->consolidateState($state);
    }
}
