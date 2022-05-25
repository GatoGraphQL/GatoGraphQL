<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class CustomPostIDsFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'customPostIDs';
    }
}
