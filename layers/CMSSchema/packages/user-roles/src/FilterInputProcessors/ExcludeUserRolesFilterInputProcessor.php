<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class ExcludeUserRolesFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'exclude-user-roles';
    }
}
