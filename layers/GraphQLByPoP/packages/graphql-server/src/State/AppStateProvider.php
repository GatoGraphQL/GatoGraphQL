<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\State;

use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

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
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $state['edit-schema'] = Request::editSchema();
        } else {
            $state['edit-schema'] = null;
        }

        $state['graphql-operation-type'] = null;

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();

        // The PQL always has nested mutations enabled. Only the for the standard GraphQL server
        // @todo Remove 'standard-graphql' and this temporary code!
        $standardGraphQL = true;//$state['standard-graphql'];
        /** @phpstan-ignore-next-line */
        $state['nested-mutations-enabled'] = $standardGraphQL ?
            $moduleConfiguration->enableNestedMutations()
            : true;

        // Check if the value has been defined by configuration. If so, use it.
        // Otherwise, use the defaults:
        // By default, Standard GraphQL has introspection enabled, and PQL is not
        $enableGraphQLIntrospection = $moduleConfiguration->enableGraphQLIntrospection();
        $state['graphql-introspection-enabled'] = $enableGraphQLIntrospection ?? $standardGraphQL;
    }

    public function consolidate(array &$state): void
    {
        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }

        $executableDocument = $state['executable-document-ast'];
        if ($executableDocument === null) {
            return;
        }

        $operationName = $state['graphql-operation-name'];

        /**
         * Set the operation type and, based on it, if mutations are supported.
         * Because with Multiple Query Execution the document can contain multiple
         * operations, use the type from the selected one, which is only one.
         */
        $requestedOperations = $executableDocument->getRequestedOperations();
        /** @var OperationInterface */
        $requestedOperation = count($requestedOperations) === 1
            ? $requestedOperations[0]
            : array_filter($requestedOperations, fn (OperationInterface $operation) => $operation->getName() === $operationName)[0];

        $state['graphql-operation-type'] = $requestedOperation->getOperationType();
        $state['are-mutations-enabled'] = $requestedOperation->getOperationType() === OperationTypes::MUTATION;
    }
}
