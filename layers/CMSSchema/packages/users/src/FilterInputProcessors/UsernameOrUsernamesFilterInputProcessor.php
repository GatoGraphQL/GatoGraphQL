<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class UsernameOrUsernamesFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(array $filterInput): string
    {
        return 'username';
    }

    protected function getValue(mixed $value): mixed
    {
        return is_array($value) ? $value : [$value];
    }
}
