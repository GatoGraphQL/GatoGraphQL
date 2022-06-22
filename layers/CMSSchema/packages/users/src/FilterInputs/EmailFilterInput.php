<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class EmailFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'emails';
    }
}
