<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants;

class Actions
{
    public const TEST_WP_CRON = 'test-wp-cron';
    public const TEST_INTERNAL_GRAPHQL_SERVER = 'test-internal-graphql-server';
    public const TEST_DEEP_NESTED_INTERNAL_GRAPHQL_SERVER = 'test-deep-nested-internal-graphql-server';
    public const TEST_INTERNAL_GRAPHQL_SERVER_NOT_READY = 'test-internal-graphql-server-not-ready';
    public const TEST_GATO_GRAPHQL_ADMIN_ENDPOINTS = 'test-gato-graphql-admin-endpoints';
    /**
     * This const is not being invoked in any test as it's the default state
     */
    public const TEST_GATO_GRAPHQL_EXECUTE_QUERY_METHOD = 'test-gato-graphql-execute-query-method';
    public const TEST_GATO_GRAPHQL_EXECUTE_QUERY_IN_FILE_METHOD = 'test-gato-graphql-execute-query-in-file-method';
    public const TEST_GATO_GRAPHQL_EXECUTE_PERSISTED_QUERY_METHOD = 'test-gato-graphql-execute-persisted-query-method';
}
