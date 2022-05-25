<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class EmailOrEmailsFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(array $filterInput): string
    {
        return 'emails';
    }

    protected function getValue(mixed $value): mixed
    {
        return is_array($value) ? $value : [$value];
    }
}
