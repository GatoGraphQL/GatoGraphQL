<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use GatoGraphQL\InternalGraphQLServer\Server\InternalGraphQLServerFactory;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;

class GraphQLServerNotReadyInternalGraphQLServerTestExecuter
{
    use GraphQLServerTestExecuterTrait;

    public function __construct()
    {
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $actions = $_GET['actions'] ?? null;
        if ($actions === null || !is_array($actions)) {
            return;
        }
        /** @var string[] $actions */
        if (!in_array(Actions::TEST_INTERNAL_GRAPHQL_SERVER_NOT_READY, $actions)) {
            return;
        }

        $this->setupToOutputOriginalExceptionMessage();

        // Just executing this code will throw the exception
        InternalGraphQLServerFactory::getInstance();
    }
}
