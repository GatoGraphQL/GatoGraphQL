<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class IsStickyFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'is-sticky';
    }
}
