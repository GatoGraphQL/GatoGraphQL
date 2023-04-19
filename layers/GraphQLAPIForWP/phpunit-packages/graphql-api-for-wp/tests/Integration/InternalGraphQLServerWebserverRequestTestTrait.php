<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\Actions;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Constants\Actions as EngineActions;

trait InternalGraphQLServerWebserverRequestTestTrait
{
    protected function getInternalGraphQLServerEndpoint(
        string $endpoint,
        bool $withDeepNested,
        bool $withNotReady = false
    ): string {
        return GeneralUtils::addQueryArgs(
            [
                'actions' => array_merge(
                    [
                        Actions::TEST_INTERNAL_GRAPHQL_SERVER,
                        EngineActions::ENABLE_APP_STATE_FIELDS,
                    ],
                    $withDeepNested
                        ? [
                            Actions::TEST_DEEP_NESTED_INTERNAL_GRAPHQL_SERVER,
                        ]
                        : [],
                    $withNotReady
                        ? [
                            Actions::TEST_INTERNAL_GRAPHQL_SERVER_NOT_READY,
                        ]
                        : []
                ),
            ],
            $endpoint
        );
    }
}
