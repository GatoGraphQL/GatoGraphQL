<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class LocationsFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'locations';
    }
}
