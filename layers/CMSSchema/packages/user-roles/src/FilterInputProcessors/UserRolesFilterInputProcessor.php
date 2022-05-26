<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class UserRolesFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'user-roles';
    }
}
