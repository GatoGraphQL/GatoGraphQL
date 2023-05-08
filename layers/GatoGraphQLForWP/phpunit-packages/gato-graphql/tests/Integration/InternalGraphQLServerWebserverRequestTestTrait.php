<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Constants\Actions as EngineActions;

trait InternalGraphQLServerWebserverRequestTestTrait
{
    /**
     * @param array<string,mixed> $options
     */
    protected function getInternalGraphQLServerEndpoint(
        string $endpoint,
        array $options
    ): string {
        /** @var bool */
        $withDeepNested = $options['withDeepNested'];
        /** @var bool */
        $withExecuteQueryInFile = $options['withExecuteQueryInFile'] ?? false;
        /** @var bool */
        $withExecutePersistedQuery = $options['withExecutePersistedQuery'] ?? false;
        /** @var bool */
        $withNotReady = $options['withNotReady'] ?? false;
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
                    $withExecuteQueryInFile
                        ? [
                            Actions::TEST_GATO_GRAPHQL_EXECUTE_QUERY_IN_FILE_METHOD,
                        ]
                        : [],
                    $withExecutePersistedQuery
                        ? [
                            Actions::TEST_GATO_GRAPHQL_EXECUTE_PERSISTED_QUERY_METHOD,
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
