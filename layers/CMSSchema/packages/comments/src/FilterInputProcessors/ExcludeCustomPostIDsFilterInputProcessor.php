<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class ExcludeCustomPostIDsFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(array $filterInput): string
    {
        return 'exclude-customPostIDs';
    }
}
