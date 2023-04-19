<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Executers;

use GraphQLAPI\GraphQLAPI\Server\InternalGraphQLServerFactory;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\Actions;

class GraphQLServerNotReadyInternalGraphQLServerTestExecuter
{
    public function __construct()
    {
        $actions = $_REQUEST['actions'];
        if ($actions === null || !is_array($actions)) {
            return;
        }
        /** @var string[] $actions */
        if (!in_array(Actions::TEST_INTERNAL_GRAPHQL_SERVER_NOT_READY, $actions)) {
            return;
        }
        $this->executeQueryAgainstInternalGraphQLServer();
    }

    protected function executeQueryAgainstInternalGraphQLServer(): void
    {
        $query = <<<GRAPHQL
            {
                id
            }
        GRAPHQL;

        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        $response = $graphQLServer->execute(
            $query,
        );

        /** @var string */
        $content = $response->getContent();
    }
}
