<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class CategoryIDsFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'category-ids';
    }
}
