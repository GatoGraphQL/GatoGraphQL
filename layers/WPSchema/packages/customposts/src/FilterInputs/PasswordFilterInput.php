<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class PasswordFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'password';
    }
}
