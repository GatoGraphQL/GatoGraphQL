<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class MimeTypesFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'mime-types';
    }
}
