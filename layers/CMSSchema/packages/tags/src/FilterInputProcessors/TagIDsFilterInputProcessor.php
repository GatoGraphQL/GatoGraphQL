<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class TagIDsFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'tag-ids';
    }
}
