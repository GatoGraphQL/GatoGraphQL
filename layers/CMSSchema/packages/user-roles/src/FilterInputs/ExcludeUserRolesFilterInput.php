<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class ExcludeUserRolesFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'exclude-user-roles';
    }
}
