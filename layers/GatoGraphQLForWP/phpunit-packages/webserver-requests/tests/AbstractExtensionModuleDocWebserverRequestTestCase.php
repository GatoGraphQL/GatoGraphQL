<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;

abstract class AbstractExtensionModuleDocWebserverRequestTestCase extends AbstractModuleDocWebserverRequestTestCase
{
    protected static function getModuleEndpoint(string $module): string
    {
        $page = 'gatographql_extensions';
        return sprintf(
            'wp-admin/admin.php?page=%s&%s=%s&%s=%s',
            $page,
            RequestParams::TAB,
            RequestParams::TAB_DOCS,
            RequestParams::MODULE,
            urlencode($module),
        );
    }
}
