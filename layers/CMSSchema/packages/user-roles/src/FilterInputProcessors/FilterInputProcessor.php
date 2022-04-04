<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_USER_ROLES = 'filterinput-user-roles';
    public final const FILTERINPUT_EXCLUDE_USER_ROLES = 'filterinput-exclude-user-roles';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_USER_ROLES],
            [self::class, self::FILTERINPUT_EXCLUDE_USER_ROLES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_USER_ROLES:
                $query['user-roles'] = $value;
                break;
            case self::FILTERINPUT_EXCLUDE_USER_ROLES:
                $query['exclude-user-roles'] = $value;
                break;
        }
    }
}
