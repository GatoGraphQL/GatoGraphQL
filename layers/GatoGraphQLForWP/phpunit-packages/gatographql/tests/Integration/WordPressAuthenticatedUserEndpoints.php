<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class WordPressAuthenticatedUserEndpoints
{
    public const ENDPOINTS = [
        'admin-client' => 'wp-admin/edit.php?page=gatographql&action=execute_query',
        'admin-unrestricted-client' => 'wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=pluginOwnUse',
    ];
}
