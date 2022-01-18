<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\State;

use PoP\Root\App;
use PoP\Root\Component as RootComponent;
use PoP\Root\ComponentConfiguration as RootComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    public function initialize(array &$state): void
    {
        $state['nested-mutations-enabled'] = null;
        $state['graphql-introspection-enabled'] = null;

        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootComponent::class)->getConfiguration();
        if ($rootComponentConfiguration->enablePassingStateViaRequest()) {
            $state['edit-schema'] = Request::editSchema();
        } else {
            $state['edit-schema'] = null;
        }
    }

    public function consolidate(array &$state): void
    {
        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }

        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();

        // The PQL always has nested mutations enabled. Only the for the standard GraphQL server
        $state['nested-mutations-enabled'] = $state['standard-graphql'] ?
            $componentConfiguration->enableNestedMutations()
            : true;

        // Check if the value has been defined by configuration. If so, use it.
        // Otherwise, use the defaults:
        // By default, Standard GraphQL has introspection enabled, and PQL is not
        $enableGraphQLIntrospection = $componentConfiguration->enableGraphQLIntrospection();
        $state['graphql-introspection-enabled'] = $enableGraphQLIntrospection !== null ?
            $enableGraphQLIntrospection
            : $state['standard-graphql'];
    }
}
