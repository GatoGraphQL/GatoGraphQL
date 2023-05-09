<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;
use GatoGraphQL\TestingSchema\Constants\Actions as TestingSchemaActions;

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
        /**
         * This persisted query, stored in the DB data, must contain
         * the same GraphQL query as ExecuteInternalQuery.gql
         *
         * @see entry `<wp:post_id>290</wp:post_id>` (or `<title><![CDATA[Internal GraphQL Server Test]]></title>`) in `gato-graphql-data.xml`
         *
         * @var string|int|null
         */
        $persistedQueryIDOrSlug = $options['persistedQueryIDOrSlug'] ?? null;
        /** @var bool */
        $withNotReady = $options['withNotReady'] ?? false;

        $queryArgs = [
            'actions' => array_merge(
                [
                    Actions::TEST_INTERNAL_GRAPHQL_SERVER,
                    TestingSchemaActions::ENABLE_APP_STATE_FIELDS,
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
        ];

        if ($withExecutePersistedQuery) {
            if (is_integer($persistedQueryIDOrSlug)) {
                $queryArgs[Params::PERSISTED_QUERY_ID] = $persistedQueryIDOrSlug;
            } elseif (is_string($persistedQueryIDOrSlug)) {
                $queryArgs[Params::PERSISTED_QUERY_SLUG] = $persistedQueryIDOrSlug;
            }
        }

        return GeneralUtils::addQueryArgs(
            $queryArgs,
            $endpoint
        );
    }
}
