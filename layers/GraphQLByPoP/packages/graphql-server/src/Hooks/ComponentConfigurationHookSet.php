<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLQuery\Environment as GraphQLQueryEnvironment;
use PoP\API\Environment as APIEnvironment;
use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\ComponentConfiguration as GraphQLRequestComponentConfiguration;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;

class ComponentConfigurationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        if (GraphQLRequestComponentConfiguration::enableMultipleQueryExecution()) {
            /**
             * Multiple query execution requires the queries to be executed in order
             */
            $hookName = ComponentConfigurationHelpers::getHookName(
                APIComponentConfiguration::class,
                APIEnvironment::EXECUTE_QUERY_BATCH_IN_STRICT_ORDER
            );
            $this->hooksAPI->addFilter(
                $hookName,
                fn () => true
            );
        }
    }
}
