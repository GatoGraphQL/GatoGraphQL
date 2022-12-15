<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class HideEmptyFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'hide-empty';
    }
}
