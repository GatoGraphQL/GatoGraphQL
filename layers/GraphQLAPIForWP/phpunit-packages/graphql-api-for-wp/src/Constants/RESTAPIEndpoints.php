<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Constants;

class RESTAPIEndpoints
{
    public const MODULE = 'wp-json/graphql-api/v1/admin/modules/%s/';
    public const MODULE_SETTINGS = 'wp-json/graphql-api/v1/admin/module-settings/%s/';
    public const CPT_BLOCK_ATTRIBUTES = 'wp-json/graphql-api/v1/admin/cpt-block-attributes/%s/%s/';
}
