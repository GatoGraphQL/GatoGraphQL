<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\State;

use PoP\Root\App;
use PoP\Root\Component as RootComponent;
use PoP\Root\ComponentConfiguration as RootComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
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
        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootComponent::class)->getConfiguration();
        if ($rootComponentConfiguration->enablePassingStateViaRequest()) {
            $state['edit-schema'] = Request::editSchema();
        } else {
            $state['edit-schema'] = null;
        }

        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();

        // The PQL always has nested mutations enabled. Only the for the standard GraphQL server
        // @todo Remove 'standard-graphql' and this temporary code!
        $standardGraphQL = true;//$state['standard-graphql'];
        /** @phpstan-ignore-next-line */
        $state['nested-mutations-enabled'] = $standardGraphQL ?
            $componentConfiguration->enableNestedMutations()
            : true;

        // Check if the value has been defined by configuration. If so, use it.
        // Otherwise, use the defaults:
        // By default, Standard GraphQL has introspection enabled, and PQL is not
        $enableGraphQLIntrospection = $componentConfiguration->enableGraphQLIntrospection();
        $state['graphql-introspection-enabled'] = $enableGraphQLIntrospection ?? $standardGraphQL;
    }
}
