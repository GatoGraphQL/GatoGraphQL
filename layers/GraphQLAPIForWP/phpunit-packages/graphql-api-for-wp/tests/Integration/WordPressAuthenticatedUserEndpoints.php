<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class WordPressAuthenticatedUserEndpoints
{
    public const ENDPOINTS = [
        'admin-client' => 'wp-admin/edit.php?page=graphql_api&action=execute_query',
        'admin-unrestricted-client' => 'wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=pluginOwnUse',
        'editing-persisted-query-client' => 'wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=persistedQuery&persisted_query_id=65',
    ];
}
