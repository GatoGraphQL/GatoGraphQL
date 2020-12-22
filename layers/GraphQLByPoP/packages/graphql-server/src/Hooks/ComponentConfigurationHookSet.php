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
    protected function init()
    {
        if (GraphQLRequestComponentConfiguration::enableMultipleQueryExecution()) {
            /**
             * Set environment variable to true because it's needed by @export
             */
            $hookName = ComponentConfigurationHelpers::getHookName(
                GraphQLQueryComponentConfiguration::class,
                GraphQLQueryEnvironment::ENABLE_VARIABLES_AS_EXPRESSIONS
            );
            $this->hooksAPI->addFilter(
                $hookName,
                fn () => true
            );
            /**
             * @export requires the queries to be executed in order
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
