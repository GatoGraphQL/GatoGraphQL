<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class IsStickyFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'is-sticky';
    }
}
