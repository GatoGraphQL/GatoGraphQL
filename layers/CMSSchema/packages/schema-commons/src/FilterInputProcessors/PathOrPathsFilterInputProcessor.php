<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class PathOrPathsFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
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
