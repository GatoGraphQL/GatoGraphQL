<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\State;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\Root\App;
use PoP\Root\State\AbstractAppStateProvider;
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
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    /**
     * @param array<string,mixed> $state
     */
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
    }
}
