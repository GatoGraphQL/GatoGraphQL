<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class PathOrPathsFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'paths';
    }

    protected function getValue(mixed $value): mixed
    {
        return is_array($value) ? $value : [$value];
    }
}
