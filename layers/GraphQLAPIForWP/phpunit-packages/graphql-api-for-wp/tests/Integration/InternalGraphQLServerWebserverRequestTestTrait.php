<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\Actions;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Constants\Actions as EngineActions;

trait InternalGraphQLServerWebserverRequestTestTrait
{
    protected function getInternalGraphQLServerEndpoint(string $endpoint): string
    {
        return GeneralUtils::addQueryArgs(
            [
                'actions' => [
                    Actions::TEST_INTERNAL_GRAPHQL_SERVER,
                    EngineActions::ENABLE_APP_STATE_FIELDS,
                ],
            ],
            $endpoint
        );
    }
}
