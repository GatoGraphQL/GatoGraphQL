<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class TagSlugsFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'tag-slugs';
    }
}
