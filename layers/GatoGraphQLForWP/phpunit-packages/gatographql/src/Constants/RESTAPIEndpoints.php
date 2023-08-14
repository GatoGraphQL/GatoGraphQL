<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Constants;

class RESTAPIEndpoints
{
    public const MODULE = 'wp-json/gatographql/v1/admin/modules/%s/';
    public const MODULE_SETTINGS = 'wp-json/gatographql/v1/admin/module-settings/%s/';
    public const CPT_BLOCK_ATTRIBUTES = 'wp-json/gatographql/v1/admin/cpt-block-attributes/%s/%s/';
}
