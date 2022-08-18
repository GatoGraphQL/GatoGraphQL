<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\State;

use GraphQLByPoP\GraphQLServer\Configuration\Request;
use GraphQLByPoP\GraphQLServer\Constants\OperationTypes;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
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
    }

    public function execute(array &$state): void
    {
        /**
         * Call ModuleConfiguration only after hooks from
         * SchemaConfigurationExecuter have been initialized.
         * That's why these are called on `execute` and not `initialize`.
         *
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $state['graphql-introspection-enabled'] = $moduleConfiguration->enableGraphQLIntrospection() ?? true;

        if (!($state['scheme'] === APISchemes::API && $state['datastructure'] === $this->getGraphQLDataStructureFormatter()->getName())) {
            return;
        }

        $executableDocument = $state['executable-document-ast'];
        if ($executableDocument === null) {
            return;
        }
        /** @var ExecutableDocument $executableDocument */

        /**
         * Set the operation type and, based on it, if mutations are supported.
         */
        $requestedOperation = $executableDocument->getRequestedOperation();
        /** @var OperationInterface $requestedOperation */
        $state['graphql-operation-type'] = $requestedOperation->getOperationType();
        $state['are-mutations-enabled'] = $requestedOperation->getOperationType() === OperationTypes::MUTATION;
    }
}
