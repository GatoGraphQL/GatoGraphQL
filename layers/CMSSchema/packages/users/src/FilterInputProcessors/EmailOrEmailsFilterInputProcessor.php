<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class EmailOrEmailsFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'emails';
    }

    protected function getValue(mixed $value): mixed
    {
        return is_array($value) ? $value : [$value];
    }
}
